function [pwc,wwp,wwq,sav,ci,c_tot,qlabels] = ihotel(rtoilet,rsink,...
	rshower,names,uses,misc,time,reuse,rtech,varargin)
	% This program simulates a hotel's water management system.  The user
	% inputs the water use of certain hotel services as well as a reuse matrix
	% and the names of the services.  The data output includes potable water,
	% wastewater, reused water and total sectorial water use matrices.  Below
	% are the needed inputs...
	% rtoilet - daily water use by room toilets (m3/day)
	% rsink - daily hotel room sink water use (m3/day)
	% rshower - daily hotel room shower/bath water use (m3/day)
	% names - cell array of names of included hotel services
	% uses - matrix of use frequencies of each of the hotel services
	% (times/day)
	% time - the total duration (in hours) of operation of the system
	% reuse matrix
	% daily water use  and uses per day of other services can also be included.
	% 
	% Next, the needed outputs are...
	% pwc - Potable water consumption over the designated number of hours 
	% (L/hour)
	% wwp - Wastewater production over the designated number of hours (L/hour) 
	% wwq - Total wastewater quality over the designated number of hours(mg/L)
	% rww - Reused water amount (L/hour)
	% ci - incoming water quality to each hotel service (mg/L)
	% c_tot - quality of treated reuse water(mg/L)
	% qlabels - names of the water quality parameters (for presentation
	% purposes)

	% First step in setting up the program is to establish the predefined
	% matrices that contain information such as water demand, loadings, reuse
	% setup, as well as the existing water quality

	% Create matrix with water users 
	% Water demand matrix
	u = [rtoilet,rsink,rshower,varargin{:}];
	u = u/24; % Make values hourly by dividing by 24 assuming constant flow
	uses = uses/24;
	% List of the names of all the hotel services
	col = numel(names);

	% Loadings will be extracted from a file that can be easily
	% modified based on new information.  

	% First extracting the information
	ldata = 'C:\Users\msantana\Dropbox\ICRA\Model Development\SAMBAflow\LoadingData.xlsx';
	rldata = 'Raw_Table';
	R = 'A1:I10';
	[loads,labels,todo] = xlsread(ldata,rldata,R); % Reading the excel file
	[na,nc] = size(loads); % Calculate the number of contaminant parameters
	clabels = cell(1,nc); % Establish the contaminant names
	cloads = zeros(col,nc); % Contaminant loads

	% Fill the cell array with labels
	v = nc+1;
	w = nc+2;
	j = 0;
	for C = 2:v
		j=j+1;
		clabels{j} = labels{1,C};
	end

	% Call to retrieve tap water quality information
	twdata = 'Tap_Water';
	Rtw =  'A1:I4';
	[twq,tlabels,all] = xlsread(ldata,twdata,Rtw);

	qlabels = tlabels(1,2:9); % water quality labels

	% Create the cell array and matrix with the chosen services
	slabels = cell(1,col);
	ctap = zeros(col,nc); % Tap water concentrations matrix
	j = 0;
	for C = 1:col
		for D = 2:w
			if strcmp(names{1,C},labels{D,1}) == 1
				j=j+1;
				B= D-1;
				slabels{1,j} = labels{D,1};
				cloads(j,:) = loads(B,:);
				ctap(j,:) = twq(1,:);
			else
				;
			end
		end
	end

	% So far, we have the following defined matrices
	% u - demand matrix with base daily water demands
	% n - list of service names
	% cloads - matrix with contaminant loads
	% ctap - matrix containing tap water concentrations

	% Now make the undefined matrices
	p = zeros(col,1); % Potable water provision matrix
	rw = zeros(col,col); % Water reuse amount matrix
	t = zeros(col,1); % Tank matrix (optional)
	c = zeros(col,col); % Percentage matrix
	ww = zeros(col,1); % Wastewater matrix
	f = zeros(col,1); % Influent water flow
	a = zeros(col,1); % Alternative sources of water

	% Water quality matrices
	ci = zeros(col,nc); % incoming water quality
	cu = zeros(col,nc); % used water quality
	cr = zeros(col,nc); % treated reused water quality
	cw = zeros(col,nc); % wastewater water quality

	% Water reuse array
	cru = cell(1,col); % Water reuse quality (post-treatment)
	rem = zeros(col,col);

	% Sum across rows to determine percentage of water diversion
	d = sum(reuse,2);
	for x = 1:col
		for y = 1:col
			if reuse(x,y)>0
				c(x,y) = 1/d(x);
				rw(x,y) = c(x,y)*u(x);  
			else
				;
			end
		end
	end


	% Sum by column to determine total water received
	w = sum(rw); % Total water used
	wg = sum(rw,2); % Total water received from reuse
	rwp = zeros(col,col);

	% Calculate percentage reuse contributions
	for x = 1:col
		for y = 1:col
			if w(y) > 0
				rwp(x,y) = rw(x,y)/w(y);
			else
				rwp(x,y) = 0;
			end
		end
	end 

	% All of the matrices will be set in a time-scale dependent loop (that can
	% be set by the user).  Defined matrices will stay the same while undefined
	% matrices will change based on the changing conditions in the loop.  

	% First prepare the defined matrices
	p = u; % All demand will be filled by potable water
	ci = ctap; % Incoming water quality

	% Create time series
	pwc = zeros(col,time);
	wwp = zeros(time,1);
	wwq = zeros(time,nc);
	sav = zeros(col,time);
	ww = u;
	rww = zeros(1,col);
	uw = u-misc;

	% Now begin the loop
	for s = 1:time
		pwc(:,s) = p; % Record the potable water consumption
		% Fill in the used water quality matrix
		for r = 1:col
			for c = 1:8
				cu(r,c) = ((u(r)*ci(r,c))+(cloads(r,c)*uses(r)))-(misc(r)*ci(r,c))/uw(r); % Add loadings
			end
		end
		% Now, take into account the reuse flows and determine the reused water
		% quality by creating a cell array that contains all of the water
		% quality of the network connections
		for r = 1:col
			for c = 1:col
				if rtech(r,c) == 1
					[qrem,chem,energy]=mbr(cu(r,:));
					qrem = qrem*reuse(r,c);
				elseif rtech(r,c) == 2
					[qrem,chem,energy]=filtchem(cu(r,:));
					qrem = qrem*reuse(r,c);
				else
					qrem = zeros(1,nc);
				end
				cru{1,c}(r,:) = qrem*rw(r,c);
			end
		end
		
		% Now aggregate all the reused water qualities and flows by the reuser
		qc_tot = zeros(col,nc);
		users = sum(rw,1);
		c_tot = zeros(col,nc);
		for r = 1:col
			qc_tot(r,:) = sum(cru{1,r},1); % Sum all loadings
		end
		for r = 1:col
			for c = 1:nc
				if users(r) > 0
					c_tot(r,c) = qc_tot(r,c)/users(r);
				else
					c_tot(r,c) = 0;
				end
			end
		end
		
		% Set up conditions for diversion to wastewater reuse
		for j = 1:col
			% Take into account the elimination of diverted wastewater
			
			if users(j) > 0 % only if received
				if users(j) > u(j)
					p(j) = 0;
					rww(1,j) = users(j)-u(j);
					a(j) = u(j);
				elseif users(j) < u(j)
					p(j) = u(j)-users(j);
					rww(1,j) = 0;
					a(j) = users(j);
				else
					p(j) = 0;
					t(j,1) = t(j,1);
					rww(1,j) = 0;
					a(j) = users(j);
				end
			else
				;
			end
			
		end
		
		sav(:,s) = a;
		
		% Allocate the unused water for reuse back to the wastewater
		for x = 1:col
			for y = 1:col
				rwpww(x,y) = rww(y)*rwp(x,y);
			end
		end
		% Sum 
		aww = transpose(sum(rwpww,2));
		
		ww = u-transpose(wg)+aww; % Calculate the wastewater associated with each use
		
		% Subtract the garden wastewater and modify the pool wastewater flow 
		for R = 1:col
			if strcmp(slabels{1,R},'G') == 1
				ww(R) = 0;
			elseif strcmp(slabels{1,R},'Pool') == 1
				ww(R) = uw(R);
			else
				;
			end
		end
		
		wwp(s) = sum(ww); % Record the amount of wastewater produced
		
		% Calculate the overall wastewater quality first by summing the
		% loadings and then dividing by the total flow
		wwl = zeros(col,nc);
		
		for R = 1:col
			wwl(R,:) = ww(R)*cu(R,:);
		end
		
		wwq(s,:) = sum(wwl)/wwp(s); % Record the effluent wastewater quality
		
		% Taking everything into account now, redefine the incoming water
		% quality
		
		for r = 1:col
			for c = 1:nc
				ci(r,c)=((p(r)*ctap(r,c))+(users(r)*c_tot(r,c)))/(p(r)+users(r));
			end
		end
	end
end
