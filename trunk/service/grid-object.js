function GridObject(args)
{
	var page_size = args['page_size'];
	var backend_address = args['backend'];
	var backend_additional_args = args['backend_args'];
	var grid_container = args['grid_container'];
	var columns = args['columns'];
	var total_count = 0;
	var tbods_count = 0;
	var load_timer = null;
	var loaded_data = {};
	var xhr_data = null;
	var load_data_args = {not_loaded: null, need_to_load: null};
	var cur_not_loaded = [];
	var scroll_div = document.createElement('div');
	scroll_div.className = "scroll_div";
	var scroll_table = document.createElement('div');
	scroll_table.className = 'scroll_table';
	scroll_div.appendChild(scroll_table);
	var header_div = document.createElement('div');
	header_div.className = 'header_div';
	grid_container.appendChild(header_div);
	grid_container.appendChild(scroll_div);
	var style_index = -1;
	var page_size_element = null;
	var not_loaded_rule_index = -1;
	var header_titles = {};
	var sort_data = {column_name: args["sort"]["column_name"], order: args["sort"]["order"]}
	var columns_css_rules_indexes = {};
	var filter_changed = true;
	var last_tbody_rule_index = -1;
	var filters = 
	{
		"text": 
			{
				"contains": "Содержит",
				"does_not_contains": "Не содержит",
				"equal": "Равно",
				"not_equal": "Не равно"
			},
		"int": 
			{
				"equal": "Равно",
				"greater": "Больше",
				"less": "Меньше",
				"not_equal": "Не равно"
			},
		"float": 
			{
				"equal": "Равно",
				"greater": "Больше",
				"less": "Меньше",
				"not_equal": "Не равно"
			}
	};
	this.init = function()
	{
		document.body.appendChild(document.createElement('style'));
		style_index = document.styleSheets.length - 1;
		not_loaded_rule_index = addStyleRule(style_index, ".scroll_table .not_loaded", "height:" + page_size * 20 + "px");
		last_tbody_rule_index = addStyleRule(style_index, ".scroll_table .last_tbody", "height: 0px");
		var filter_compare;
		var filter_input;
		var div_filter_compare;
		var div_filter_input;
		var filter_option;
		for(var i = 0; i < columns.length; i++)
		{
			columns[i]["rule_index"] = addStyleRule(style_index, ".scroll_table ." + columns[i].prog_name, " ");
			setRuleProp(style_index, columns[i]["rule_index"], "overflow", "hidden");
			setRuleProp(style_index, columns[i]["rule_index"], "whiteSpace", "nowrap");
			columns[i]['header_obj'] = document.createElement('div');
			header_titles[columns[i]["prog_name"]] = document.createElement("div");
			header_titles[columns[i]["prog_name"]].className = "header_title";
			header_titles[columns[i]["prog_name"]].columnName = columns[i]["prog_name"];
//			&#9650; &#9660;
			var asc_direction = document.createElement("div");
			asc_direction.className = "asc_direction";
			asc_direction.innerHTML = "&#9650";
			header_titles[columns[i]["prog_name"]].appendChild(asc_direction);
			var desc_direction = document.createElement("div");
			desc_direction.className = "desc_direction";
			desc_direction.innerHTML = "&#9660";
			header_titles[columns[i]["prog_name"]].appendChild(desc_direction);
			if(sort_data["column_name"] == columns[i]["prog_name"])
			{
				header_titles[columns[i]["prog_name"]].className += " header_title_" + sort_data["order"];
			}
			addEvent(header_titles[columns[i]["prog_name"]], "click", this.changeSort);
			header_titles[columns[i]["prog_name"]].appendChild(document.createTextNode(columns[i]["title"]));
			columns[i]['header_obj'].appendChild(header_titles[columns[i]["prog_name"]]);
			if(typeof(columns[i]['filter_type']) != "undefined" && typeof(filters[columns[i]['filter_type']]) != "undefined")
			{
				div_filter_compare = document.createElement('div');
				div_filter_compare.className = 'filter_compare ' + 'filter_compare_' + columns[i]['filter_type'];
				div_filter_input = document.createElement('div');
				div_filter_input.className = 'filter_input ' +  'filter_input_' + columns[i]['filter_type'];
				columns[i]['filter_compare'] = document.createElement("select");
				columns[i]['filter_input'] = document.createElement("input");
				columns[i]['filter_input'].type = "text";
				addEvent(columns[i]['filter_compare'], 'change', this.onFilterChange);
				addEvent(columns[i]['filter_input'], 'change', this.onFilterChange);
				div_filter_input.appendChild(columns[i]['filter_input']);
				div_filter_compare.appendChild(columns[i]['filter_compare']);
				columns[i]['header_obj'].appendChild(div_filter_compare);
				columns[i]['header_obj'].appendChild(div_filter_input);
				var filter_option;
				for(filter_name in filters[columns[i]['filter_type']])
				{
					filter_option = document.createElement("option");
					filter_option.value = filter_name;
					filter_option.appendChild(document.createTextNode(filters[columns[i]['filter_type']][filter_name]));
					columns[i]['filter_compare'].appendChild(filter_option);
				}
			}
			header_div.appendChild(columns[i]['header_obj']);
			columns[i].width = parseInt(columns[i].width)*0.01;
		}
		var reload_button = document.createElement('div');
		reload_button.className = "reload_button";
		reload_button.appendChild(document.createTextNode("Go"));
		addEvent(reload_button, 'click', this.beginLoad);
		header_div.appendChild(reload_button);
		page_size_element = document.createElement("input");
		page_size_element.type = "text";
		page_size_element.value = page_size;
		page_size_element.className = "page_size_element";
		header_div.appendChild(page_size_element);
		addEvent(scroll_div, 'scroll', this.onTableScroll);
		addEvent(window, 'resize', this.resizeScroll);
	}
	this.onFilterChange = function()
	{
		filter_changed = true;
	}
	this.changeSort = function(event)
	{
		var el;
		if (!event) var event = window.event;
		if (event.target) el = event.target;
		else if (event.srcElement) el = event.srcElement;
		if(el)
		{
			if(sort_data["column_name"] == el.columnName)
			{
				sort_data["order"] = (sort_data["order"] == "asc" ? "desc" : "asc");
			}
			else
			{
				header_titles[sort_data["column_name"]].className = "header_title";
				sort_data["column_name"] = el.columnName;
			}
			el.className = "header_title header_title_" + sort_data["order"];
		}
	}.bindAsEventListener(this);
	this.buildList = function()
	{
		var tr;
		var td;
		var tbody;
		for(var i = 0; i < tbods_count; i++)
		{
			tbody = document.createElement('div');
			tbody.id = "tbody_" + i;
			tbody.className = 'not_loaded';
			if(i%2)
				tbody.className += ' tbody_gray';
			//td = document.createElement('div');
			if(i == (tbods_count - 1))
			{
				tbody.className += ' last_tbody';
			}
			tbody.appendChild(document.createTextNode(i*page_size));
			//tbody.appendChild(td);
			scroll_table.appendChild(tbody);
		}
		this.onTableScroll();
		this.resizeScroll();
	}
	this.resizeScroll = function()
	{
		scroll_div.style.height = parseInt(grid_container.style.height) - 36 + "px";
		var cur_width = scroll_div.offsetWidth - 18;
		var col_width;
		for(var i = 0; i < columns.length; i++)
		{
			col_width = parseInt(cur_width*columns[i].width) + 'px';
			setRuleProp(style_index, columns[i]["rule_index"], "width", col_width);
			columns[i]['header_obj'].style.width = col_width;
		}
		this.onTableScroll();
	}.bindAsEventListener(this);
	this.onTableScroll = function()
	{
		var scroll_top = scroll_div.scrollTop;
		var tbody_num = parseInt(scroll_top/(page_size*20));
		var count_visible_float = parseInt(scroll_div.style.height)/(page_size*20);
		var count_visible = parseInt(count_visible_float);
		if(count_visible_float > count_visible)
		{
			count_visible++;
		}
		var need_to_load = [];
		for(var i = 0; i < count_visible; i++)
		{
			if((tbody_num + i) < tbods_count)
			{
				need_to_load.push(tbody_num + i);
			}
			else
			{
				break;
			}
		}
		if(need_to_load[need_to_load.length - 1] < (tbods_count - 1))
			need_to_load.push(need_to_load[need_to_load.length - 1] + 1);
		if(need_to_load[0] > 0)
			need_to_load.push(need_to_load[0] - 1);
		var not_loaded = [];
		for(var i = 0; i < need_to_load.length; i++)
		{
			if(typeof(loaded_data[need_to_load[i]]) == "undefined")
			{
				not_loaded.push(need_to_load[i]);
			}
		}
		not_loaded.sort();
		if(not_loaded.length && xhr_data == null)
		{
			var is_same_not_loaded = (not_loaded.length == cur_not_loaded.length);
			if(is_same_not_loaded)
			{
				for(var i = 0; i < not_loaded.length; i++)
				{
					if(not_loaded[i] != cur_not_loaded[i])
					{
						is_same_not_loaded = false;
						break;
					}
				}
			}
			if(!is_same_not_loaded)
			{
				if(load_timer)
					clearTimeout(load_timer);
				load_data_args["not_loaded"] = not_loaded;
				load_data_args["need_to_load"] = need_to_load;
				load_timer = setTimeout(this.loadData, 1000);
			}
		}
		cur_not_loaded = not_loaded;
	}.bindAsEventListener(this);
	this.getFilter = function()
	{
		var cur_filter = {filter_compare: [], filter_values: []};
		for(var i = 0; i < columns.length; i++)
		{
			if(columns[i]['filter_input'].value.length > 0)
			{
				cur_filter["filter_compare"][columns[i]['prog_name']] = columns[i]['filter_compare'].options[columns[i]['filter_compare'].selectedIndex].value;
				cur_filter["filter_values"][columns[i]['prog_name']] = columns[i]['filter_input'].value;
			}
		}
		return cur_filter;
	}
	this.loadData = function()
	{
		var not_loaded = load_data_args["not_loaded"];
		var need_to_load = load_data_args["need_to_load"];
		var start = Array.min(not_loaded)*page_size;
		var count = (Array.max(not_loaded)+1)*page_size - start;
		var post_data = {};
		for(var name in backend_additional_args)
		{
			post_data[name] = backend_additional_args[name];
		}
		var cur_filter = this.getFilter();
		post_data["start"] = start;
		post_data["count"] = count;
		post_data["filter_compare"] = cur_filter["filter_compare"];
		post_data["filter_values"] = cur_filter["filter_values"];
		post_data["sort_column_name"] = sort_data["column_name"];
		post_data["sort_order"] = sort_data["order"];
		xhr_data = sendPost(
			backend_address,
			post_data,
			function(xhr){
				xhr_data = null;
				if(xhr.readyState == 4 && xhr.status == 200)
				{
					var min_need_to_load = Array.min(need_to_load);
					var max_need_to_load = Array.max(need_to_load);
					var cur_tbody;
					var new_tbody;
					var tr;
					var td;
					var wbr;
					for(var id in loaded_data)
					{
						if(id < min_need_to_load || id > max_need_to_load)
						{
							cur_tbody = document.getElementById('tbody_' + id);
							new_tbody = document.createElement('div');
							new_tbody.className = 'not_loaded';
							new_tbody.id = "tbody_" + id;
							if(id == (tbods_count - 1))
							{
								new_tbody.className += ' last_tbody';
							}
							if(id%2)
								new_tbody.className += ' tbody_gray';
							new_tbody.appendChild(document.createTextNode(id*page_size));
							scroll_table.replaceChild(new_tbody, cur_tbody);
						}
					}
					var data = JSON.parse(xhr.responseText);
					var min_not_loaded = Array.min(not_loaded);
					var max_not_loaded = Array.max(not_loaded);
					var start;
					var div;
					var end;
					for(var id = min_not_loaded; id <= max_not_loaded; id++)
					{
						//load here
						cur_tbody = document.getElementById('tbody_' + id);
						new_tbody = document.createElement('div');
						new_tbody.id = "tbody_" + id;
						if(id%2)
							new_tbody.className = "tbody_gray";
						start = ((id - min_not_loaded) * page_size);
						end = start + page_size;
						for(var i = start; i < end; i++)
						{
							if(typeof(data[i]) == "undefined")
							{
								break;
							}
							for(var j = 0; j < columns.length; j++)
							{
								div = document.createElement('div');
								div.className = columns[j]['prog_name'] + " loaded"
								div.innerHTML = data[i][columns[j]['prog_name']];
								new_tbody.appendChild(div);
							}
							wbr = document.createElement('div');
							wbr.className = "wbr";
							new_tbody.appendChild(wbr);
						}
						scroll_table.replaceChild(new_tbody, cur_tbody);
					}
					loaded_data = {};
					for(var i = 0; i < need_to_load.length; i++)
					{
						loaded_data[need_to_load[i]] = null;
					}
				}
			});
		if(load_timer)
		{
			clearTimeout(load_timer);
		}
		load_timer = null;
	}.bind(this);
	this.beginLoad = function()
	{
		total_count = 0;
		scroll_table.innerHTML = "";
		loaded_data = {};
		page_size = parseInt(page_size_element.value);
		cur_not_loaded = [];
		setRuleProp(style_index, not_loaded_rule_index, "height", page_size*20 + "px");
		if(filter_changed)
		{
			filter_changed = false;
			var post_data = {};
			for(var name in backend_additional_args)
			{
				post_data[name] = backend_additional_args[name];
			}
			post_data["subaction"] = "getcount";
			var cur_filter = this.getFilter();
			post_data["filter_compare"] = cur_filter["filter_compare"];
			post_data["filter_values"] = cur_filter["filter_values"];
			xhr_data = sendPost(
				backend_address,
				post_data,
				function(xhr){
					xhr_data = null;
					if(xhr.readyState == 4 && xhr.status == 200)
					{
						total_count = JSON.parse(xhr.responseText);
						tbods_count = parseInt(total_count/page_size);
						if(total_count%page_size)
						{
							setRuleProp(style_index, last_tbody_rule_index, "height", (total_count - tbods_count*page_size)*20 + "px");
							tbods_count++;
						}
						else
						{
							setRuleProp(style_index, last_tbody_rule_index, "height", page_size*20 + "px");
						}
						this.buildList();
					}
				}.bind(this));
		}
		else
		{
			this.buildList();
		}
	}.bind(this);
	this.init();
	this.beginLoad();
}
