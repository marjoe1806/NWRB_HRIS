$(document).ready(function(){
	var divisionid = $('input[name="DivisionId"]').val();
	var userid = $('input[name="userid"]').val();
	var currentdate = $('input[name="curr_date"]').val();
	var base_url = commons.baseurl;
	var chart;
	getArchivesPendings(divisionid);
	getApprovedDocuments(divisionid);
	getToDoList(userid,currentdate);
	$(document).on('click','.close',function(e){
		url = commons.baseurl+"home/Home/inactiveTask";
	    id = $(this).parent().data('id');
	    me = $(this)
	    $.ajax({
	        type: "POST",
	        url: url,
	        data: {id:id},
	        dataType: "json",
	        success: function(result){
	            if(result.hasOwnProperty("key")){
	                if(result.Code == "0"){
	                    if(result.hasOwnProperty("key")){
	                        switch(result.key){
	                            case 'inactiveTask':
		   							me.parent().fadeOut();
	                                break;
	                        }
	                    }  
	                }
	                else{
	                    $.alert({
					    	type:"red",
					    	title:'<label class="text-danger">Failed</label>',
					    	content:"Failed to read Task!"
					    });
	                }
	            }
	        },
	        error: function(result){
	            $.alert({
			    	type:"red",
			    	title:'<label class="text-danger">Failed</label>',
			    	content:"There was an error in the connection please try again later."
			    });
	        }
	    });
	})
	var initiate_data = function() {
		var temp = null;
		url2 = base_url + "queryglobal/QueryGlobal";
        data2 = {"query":"4EA4444894FDB6770252864FACC79D6154B46A3FAD025D730A84953D3BE598C2FB51BCEB531D0D87C831AAED5D5FCB8E254DB4652E04DC0372C754D41C844C9DF234C1C51FC7D3AD6F48CBD5989369E69046E7B41A38983225DC584A7EA984EC694FE16F81164FDF3723652929F8ED78BC0E90D71399328489DEDABEB0F381F05114CC5C306588F417D0F99E2CEECD962EA9154858C6973D293C12E8A84884C9D9F5608D30FE7AE50892A816C2B43A8BC28CB6D28215043A3EFE9B5B41A441B7A7F9A44D9CDBC561CB170E94D60F90C1CA1FC3740E41BD15F5B408060E89B1BC36BB231A4F6EC750249F4C59315B7EFE623C0E25E50C30BFD593752C7481A0332DE602FB58C1AD086FCFA1B75E402DA9FE91169A564802F996E44E1C663CFDB026074C4E4DE3194FB285E3BE74144C2F366A3FC2538340E35A134F653D9F5FCF8673DBBCA6D712772B3405487F5962E938DBCC957553D0741E48A5B48DA8A05AAD30B0FE6D45082E0FD047D19F9F2ADCC01D9EC5B5FC8BB7E784AC6567480B285615FD064A506D06AB1291D64FE457169F",
                "params":[]};
        $.ajax({
        	async: false,
            url: url2,
            data: data2,
            type:'POST',
            dataType:'JSON',
            success: function(res) {
            	temp = res;
            	//console.log("meron "+res);
            }
        });  
        
		return temp;
	}();
	var max;
	var min;
	if(initiate_data.hasOwnProperty("Data")){
		var max = new Date(initiate_data.Data.details[0].maxdate);
		var min = new Date(initiate_data.Data.details[0].mindate);
	}
	var allYears = getBetweenYears(max,min);
	getYears = populateYears(allYears)
	jsonData = getMonthlyData();
	//json = JSON.parse(jsonData)
	//console.log(jsonData)
	chart = initiate_chart(jsonData,"month",["total_files"],["File(s)"])
	function initiate_chart(data,xkey,ykeys,labels){
		xLabelAngle = 30;
		if(data){
			if(data[0].hasOwnProperty("type")){
				xLabelAngle = 5;
			}
		}
		myChart = new Morris.Bar({
			element: "morrisGraphBars",
			data:data,
			xkey: xkey,
			ykeys: ykeys,
			labels: labels,
			barSize:40,
			barColors: function (row, series, type) {
				//Change color
				/*console.log(row);
				y = 
				if(row.y > )*/
				return "#286abd";
			},
			xLabelAngle: xLabelAngle,
			hideHover: "auto",
			hoverCallback: function(index, options, content) {
		        var data = options.data[index];
		        if(data.hasOwnProperty("type")){
		        	tooltip_content = '<div class="morris-hover-row-label">'+data.division_name+'</div>'+
					'<div class="morris-hover-point">'+
		        	'Document Type: '+data.type+ 
					'</div>'+
					'<div class="morris-hover-point">'+
		        	'Total Files: '+data.total_files+ 
					'</div>'+
					'<div class="morris-hover-point">'+
		        	'Total Size: '+humanFileSize(data.total_size,true)+
					'</div>';
		        	return tooltip_content;
		        }
		        else if(data.hasOwnProperty("division")){
		        	tooltip_content = '<div class="morris-hover-row-label">'+data.division+'</div>'+
		        	'<div class="morris-hover-point">'+
		        	'Total Files: '+data.total_files+ 
					'</div>'+
		        	'<div class="morris-hover-point">'+
		        	'Total Size: '+humanFileSize(data.total_size,true)+ 
					'</div>';
		        	return tooltip_content;
		        }
		        else{
		        	tooltip_content = '<div class="morris-hover-row-label">'+data.month+'</div>'+
		        	'<div class="morris-hover-point">'+
		        	'Total Files: '+data.total_files+ 
					'</div>'+
		        	'<div class="morris-hover-point">'+
		        	'Total Size: '+humanFileSize(data.total_size,true)+ 
					'</div>';
		        	return tooltip_content;
		        }
		    },
			resize: false
		})
		$('.file-size').remove();
		$('.total-files').remove();	
		console.log(data);
		var allocatedFileSize = 0;
		var allocatedFiles = 0;
		if(data){
			$.each(data,function(i,v){
				if(v.hasOwnProperty('total_size')){
					allocatedFileSize += parseInt(v.total_size);
				}
				if(v.hasOwnProperty('total_files')){
					allocatedFiles += parseInt(v.total_files);
				}
			});		
		}
		$('#graph_holder').append('<center><div class="file-size"><label class="text-primary"><b>Total File Size: </b></label><span> '+humanFileSize(allocatedFileSize,true)+'</span></div></center>')
		$('#graph_holder').append('<center><div class="total-files"><label class="text-primary"><b>Total File(s): </b></label><span> '+allocatedFiles+'</span></div></center>')
		
    	return myChart;
	}
	//Go-back Chart
	$(document).on('click','.go_back',function(e){
		e.preventDefault();
		me = $(this)
		$("#back_filter").remove();
		$("#morrisGraphBars").remove();
		
		
		switch(me.attr('data-code'))
		{
			case 'getMonthlyData':
				data = getMonthlyData(divisionid)
				if(data > 10){
					size = 60 * data;
					unit = 'px';
				}
				else{
					size = 100;
					unit = '%';
				}
				$("#graph_holder").append('<div id="morrisGraphBars" style="width:'+size+unit+'"></div>');
				chart = initiate_chart(data,"month",["total_files"],["File(s)"]);
				return chart.on('click',function(i,data){
					year = $('#year_filter').val();
					data = getMonthlyDivisionsData(data.id)
					//console.log(data)
					if(data.length > 10){
						size = 60 * data.length;
						unit = 'px';
					}
					else{
						size = 100;
						unit = '%';
					}

					$("#morrisGraphBars").remove();
					$("#back_filter").remove();
					$("#graph_holder").append('<div id="morrisGraphBars" style="width:'+size+unit+'"></div>');
					back_link = base_url + "statistics/statistics_ctrl/getMonthlyData";
					code  = 'getMonthlyData';
					chart = initiate_chart(data,"division",["total_files"],["File Size"]);
					$('<div id="back_filter" class="col-md-12 col-sm-12 col-xs-12 text-right"><br>'+
											'<a class="go_back btn btn-primary btn-fill" data-code="'+code+'" href="'+back_link+'">'+
											' Go Back</a>'+
											'</div>').insertAfter("#graph_holder");
					return chart.on('click',function(i,data){
						divisionid = data.divisionid
						month = data.month
						$("#morrisGraphBars").remove();
						$("#back_filter").remove();
						data = getMonthlyDivisionsTypeData(month,divisionid);
						code = "getMonthlyDivisionsData";
						if(data.length > 10){
							size = 60 * data.length;
							unit = 'px';
						}
						else{
							size = 100;
							unit = '%';
						}
						$("#graph_holder").append('<div id="morrisGraphBars" style="width:'+size+unit+'"></div>');
						chart = initiate_chart(data,"type",["total_files"],["File Size"]);
						$('<div id="back_filter" class="col-md-12 col-sm-12 col-xs-12 text-right"><br>'+
							'<a class="go_back btn btn-primary btn-fill" data-code="'+code+'" data-month="'+month+'" href="#">'+
							' Go Back</a>'+
							'</div>').insertAfter("#graph_holder");
					})
				});
				break;
			case 'getMonthlyDivisionsData':
				code = "getMonthlyData";
				data = getMonthlyDivisionsData(me.data('month'));
				if(data > 10){
					size = 60 * data;
					unit = 'px';
				}
				else{
					size = 100;
					unit = '%';
				}
				$("#graph_holder").append('<div id="morrisGraphBars" style="width:'+size+unit+'"></div>');
				chart = initiate_chart(data,"division",["total_files"],["File Size"]);
				$('<div id="back_filter" class="col-md-12 col-sm-12 col-xs-12 text-right"><br>'+
						'<a class="go_back btn btn-primary btn-fill" data-code="'+code+'" data-month="'+me.data('month')+'" href="#">'+
						' Go Back</a>'+
						'</div>').insertAfter("#graph_holder");
				return chart.on('click',function(i,data){
					divisionid = data.divisionid
					month = data.month
					$("#morrisGraphBars").remove();
					$("#back_filter").remove();
					data = getMonthlyDivisionsTypeData(month,divisionid);
					code = "getMonthlyDivisionsData";
					if(data.length > 10){
						size = 60 * data.length;
						unit = 'px';
					}
					else{
						size = 100;
						unit = '%';
					}
					$("#graph_holder").append('<div id="morrisGraphBars" style="width:'+size+unit+'"></div>');
					chart = initiate_chart(data,"type",["total_files"],["File Size"]);
					$('<div id="back_filter" class="col-md-12 col-sm-12 col-xs-12 text-right"><br>'+
						'<a class="go_back btn btn-primary btn-fill" data-code="'+code+'" data-month="'+month+'" href="#">'+
						' Go Back</a>'+
						'</div>').insertAfter("#graph_holder");
				})
				break;
		}
		
		/*return chart.on('click',function(i,data){
			
			$("#morrisGraphBars").remove();
			$("#back_filter").remove();
			if(me.attr('data-code') == "getMonthlyData"){
				data = getMonthlyDivisionsData(data.id);
				code = "getMonthlyData";
			}
			else if(me.attr('data-code') == "getMonthlyDivisionsData"){
				data = getMonthlyDivisionsTypeData(data.month,data.division);
				code = "getMonthlyDivisionsData";
				
			}
			if(data.length > 10){
				size = 60 * data.length;
				unit = 'px';
			}
			else{
				size = 100;
				unit = '%';
			}
			$("#graph_holder").append('<div id="morrisGraphBars" style="width:'+size+unit+'"></div>');
			if(me.attr('data-code') == "getMonthlyData"){
				chart = initiate_chart(data,"division",["total_files"],["File Size"]);
				$('<div id="back_filter" class="col-md-12 col-sm-12 col-xs-12 text-right"><br>'+
					'<a class="go_back btn btn-primary btn-fill" data-code="'+code+'" href="#">'+
					' Go Back</a>'+
					'</div>').insertAfter("#graph_holder");
			}
			else if(me.attr('data-code') == "getMonthlyDivisionsData"){
				chart = initiate_chart(data,"type",["total_files"],["File Size"]);
				$('<div id="back_filter" class="col-md-12 col-sm-12 col-xs-12 text-right"><br>'+
				'<a class="go_back btn btn-primary btn-fill" data-code="'+code+'" data-month="'+data.month+'" href="#">'+
				' Go Back</a>'+
				'</div>').insertAfter("#graph_holder");

			}
		})*/
	})
	//Click Initiated Chart
	chart.on('click',function(i,data){
		year = $('#year_filter').val();
		data = getMonthlyDivisionsData(data.id)
		//console.log(data)
		if(data.length > 10){
			size = 60 * data.length;
			unit = 'px';
		}
		else{
			size = 100;
			unit = '%';
		}

		$("#morrisGraphBars").remove();
		$("#back_filter").remove();
		$("#graph_holder").append('<div id="morrisGraphBars" style="width:'+size+unit+'"></div>');
		back_link = base_url + "statistics/statistics_ctrl/getMonthlyData";
		code  = 'getMonthlyData';
		chart = initiate_chart(data,"division",["total_files"],["File Size"]);
		$('<div id="back_filter" class="col-md-12 col-sm-12 col-xs-12 text-right"><br>'+
								'<a class="go_back btn btn-primary btn-fill" data-code="'+code+'" href="'+back_link+'">'+
								' Go Back</a>'+
								'</div>').insertAfter("#graph_holder");
		//Click Divisions
		return chart.on('click',function(i,data){
			divisionid = data.divisionid
			month = data.month
			$("#morrisGraphBars").remove();
			$("#back_filter").remove();
			data = getMonthlyDivisionsTypeData(month,divisionid);
			code = "getMonthlyDivisionsData";
			if(data.length > 10){
				size = 60 * data.length;
				unit = 'px';
			}
			else{
				size = 100;
				unit = '%';
			}
			$("#graph_holder").append('<div id="morrisGraphBars" style="width:'+size+unit+'"></div>');
			chart = initiate_chart(data,"type",["total_files"],["File Size"]);
			$('<div id="back_filter" class="col-md-12 col-sm-12 col-xs-12 text-right"><br>'+
				'<a class="go_back btn btn-primary btn-fill" data-code="'+code+'" data-month="'+month+'" href="#">'+
				' Go Back</a>'+
				'</div>').insertAfter("#graph_holder");
		})
	})
	//Reusable functions
	function getToDoList(userid,current_date){
		var toDoList = function() {
			var temp = null;
			url2 = base_url + "queryglobal/QueryGlobal";
	        data2 = {"query":"4EA4444894FDB677C114A1B055F21E93C7F14DA5C7F5691249E2ED4A6527A33CEA13309F40302ADD64F6120537BA0116A619D2D744A6D33ABF0B91A75D84F7DC2A1453E035E845A975B46C297799A1ABEED132297CF851DA7A0B539412A2D8BB65B471B641645FB5",
	                "params":[userid,current_date]};
	        $.ajax({
	        	async: false,
	            url: url2,
	            data: data2,
	            type:'POST',
	            dataType:'JSON',
	            success: function(res) {
	            	temp = res;
	            	//console.log("meron "+res);
	            }
	        });  
	        
			return temp;
		}();

		console.log(toDoList)
		if(toDoList.Code == "0"){
			if(toDoList.hasOwnProperty('Data')){
				var li="";
				$.each(toDoList.Data.details,function(i,v){
					checked = ""
					if(v.is_read == "1"){
						checked = "checked"
					}
					li += '<li class="'+checked+'" data-id="'+v.id+'">'+v.title+'<span class="close">×</span></li>'
				})
				$('#myUL').html(li);
			}
		}
		
		
	}
	//Count For dashboard
	function getArchivesPendings(divisionid){
		//console.log(divisionid)
		var countPending = function() {
			var temp = null;
			url2 = base_url + "home/Home/getDraftCount";
	        $.ajax({
	        	async: false,
	            url: url2,
	            type:'POST',
	            dataType:'JSON',
	            success: function(res) {
	            	temp = res;
	            	//console.log("meron "+res);
	            }
	        });  
	        
			return temp;
		}();
		console.log(countPending)
		if(countPending.hasOwnProperty('Data')){
			$('.pendingarchives').find('div.count').html(countPending.Data.count);	
		}
		
	}
	function getApprovedDocuments(divisionid){
		var countPending = function() {
			var temp = null;
			url2 = base_url + "home/Home/getApprovedCount";
	        $.ajax({
	        	async: false,
	            url: url2,
	            type:'POST',
	            dataType:'JSON',
	            success: function(res) {
	            	temp = res;
	            	//console.log("meron "+res);
	            }
	        });  
	        
			return temp;
		}();
		console.log(countPending)
		if(countPending.hasOwnProperty('Data')){
			$('.approvedDocuments').find('div.count').html(countPending.Data.count);	
		}
		
	}
	function getBetweenYears(from, to) {
	    var d1 = new Date(from),
	        d2 = new Date(to),
	        yr = [];

	    for (var i=d1.getFullYear(); i<=d2.getFullYear(); i++) {
	        yr.push( i );
	    }

	    return yr;
	}
	function populateYears(allYears){
        var option;
        selected = new Date().getFullYear();
        $.each(allYears,function(i,v){
            sel = "";
            if(selected == v){
                sel = "selected";
            }
            option += '<option value="'+v+'" '+sel+'>'+v+'</option>';
        })
        $('#year_filter').html(option)
	}
	function getMonthlyData(){
		year = $('#year_filter').val();
		var monthlyData = function() {
			var temp = null;
			url2 = base_url + "queryglobal/QueryGlobal";
	        data2 = {"query":"4EA4444894FDB6779AA4F1CA5C691D67A9E221B03712BF02D088F890AA6E45601D5397204FB33099F4E32081EB6B07A96C70A729ECCF819F0A1D17435F1A5C71490C84F1A37377EFC7156A59F2AE618333B9D6A6C9817951C4C60BB4F016AC024A2BC9DBCE8CC34D5B0981CB2C920FD07FB08CED5EB9BCD3053B9E4577F4B6F5750634DAF8EC7D0272829E42BA134DB42ADA6B4F121727B26FE4F0925908C489996D150B9011391D3EB1DB04EC07C76D07ACA59A49284001280F42A0F0EB9455D20C8EC3297A4878B16D74EEAC75EABBB9BF25CCC191AC91AFD51DBFB0B3FFA45AAFDD6B46DD2648C6F1FB2E762E406CBEA5628441BDBB4321293BB2DADBE60E0606C74BD225E0B6B2D622D957CB1330E65640DFA50788885144EDBB1844A5F5D134E50A2DD30F42711DBDFC8550742EC858209E0F87B0572A79C94F9FD23A217322727E0228693BE32970C1BC820C317D5FAFB49461A1403148E154A0BB380614917D4B47B20F0A59BA44C0928A087B240CB563095B98FF077A62D6736D2E7992544CB9611233A3059B6E59AC2F711DD550ED8CB4DE2C28831DB08B3212FA91CC79CFDF4ACB4EEABE6CC99A129741AF770C2D9F9976BDA604E20929872E68E92A2A7AD7BC919AB8E0139B7928EDE66AE88CB838EF0F39C6C75593E6A41634A425AC62DDD4EBAB0930317FE9F0593879A85E61D4780BBE2E1EBCEBFA2B38BC25729D610B9EE2948ABC61E18260527B09C2D5862285009B54478429E02C0140AD2540B4788590865970090E94503C097CF100BE827939A4FE10EB8E132BD40053BD2E0A441D7FAFD87F8F45F0435BFB6ED53769475D23702CA0B6C12BEF2587DBC39C81F4BB58DC56912B84055642E179849FA7991CAEA8924DA78DA7396DD68399",
	                "params":[year,year]};
	        $.ajax({
	        	async: false,
	            url: url2,
	            data: data2,
	            type:'POST',
	            dataType:'JSON',
	            success: function(res) {
	            	temp = res;
	            	//console.log("meron "+res);
	            }
	        });  
	        
			return temp;
		}();
		if(monthlyData.hasOwnProperty('Data')){
			/*$.each(monthlyData.Data.details,function(i,v){
				monthlyData.Data.details[i].total_size = humanFileSize(monthlyData.Data.details[i].total_size,true)
			})*/
			return monthlyData.Data.details;	
		}
		
	}
	
	function getMonthlyDivisionsData(id){
		year = $('#year_filter').val();
		var monthlyData = function() {
			var temp = null;
			url2 = base_url + "queryglobal/QueryGlobal";
	        data2 = {"query":"4EA4444894FDB6770D0D6B1FF4B40F51F80F786E8BFADE3AE14862B621FC32EA50615BB228C5EF14E183AE8E49389C0171BAE48814CE267BD471A431165315380045CDB8EA3A3EA68E5C2310F36BBF25AC4D853ACEFE01F959B490676CA1D05E7D6690FEBD05F8A97E179FD5328C11CE61AE92F340A7A2CD1B25805B69EAA8EB6B182AC459894041C142575123E4876A509A2B6F7AFFFCB3AA3A560DADC1D7106E7FFEF44694076F4A85CD3F0E85CAB2D834BAEABA98D02869B140AD6A16C8F91D6F737032B35283B30CCB3DFDA891CAA927B58AEF497DB9DF1916CD3B9D41E30AC1F1864DA9E32E543C91913302CE22634A8FB591B5F10485F68F0A6A77B8AAB9EBB2B8C5287DC8B97F4AD8DE77C642190FE38B8D21A0CED5A16D308D4B6F7C717D65F341BD872A4093C325A4639B4FEFD6817CCA1803610EF1C679B0CA84E53C4F91A7CBD5964D0594FE30A1F0E9779212D82D2842226B4D08DBA32F5F4A0228074CC09E92D55CD6F53B8D94C1D8F99C2B2DAF076F5D147D87080FF7531A437B5051DA6C65EDDFB448D300B4C23A319D4BC7EF6C8879A1DCAFDB1C8CCEDDC39DB2666BB122562E7AC3F1448FEAC1A5833F61A9DE064077F9B4DD959011E3A1A830E6F9EBF61F9EB8A2D7C55C8A558F27B311D634802F0C0D932C326D6AA35B93CE3F609C2C9444A378CE592764399B8B131E21CD98D81F254F90CA7A8EABE0B91167D800351F7E366BA0A45B13ADD2858380BCE0EF9990D84963260584606F586E3F1B73087D350697B5570A869B2E798F1E7990C64E9541A1F578FBFCE7BF71ECBDBF2F2794E39A167EB2618F2A1DCD3F48809DA28805DCB657354EA353A77CC48FE722B639AFA497F3699EA5C19E2FF3C39BC27A2B1987C8A86A6D846967B0EE8C5635C6D1E78C9F2B5452AD591E5DA2A60CB264914CC9F194BB67E3CE2434669214AF783A0EAD926690540ABE9A8235736C1C5979E0A6E3498CE6408CAAC3D1C72227D5A44CADD961C83DC03D98E471926FCFC58B7902BA1E0097BE8AE9014DBC78538F3AF112CB399966412D1DA2C57029C843454AAFC7DB598BA36F11645CE060AABE8B8E629972924C60619A2253C150EA593726DB1C7546F3281E60EA526392CDF8DAC5A0F84B513E4B85BE8A440707A39113ABD2591F9FF7E9B2AEC9DFBDA6F5615FF28AB1EF0A54",
                "params":[id,year,id]};
	        $.ajax({
	        	async: false,
	            url: url2,
	            data: data2,
	            type:'POST',
	            dataType:'JSON',
	            success: function(res) {
	            	temp = res;
	            	console.log(temp)
	            	//console.log("meron "+res);
	            }
	        });  
	        
			return temp;
		}();
		/*$.each(monthlyData.Data.details,function(i,v){
			monthlyData.Data.details[i].total_size = humanFileSize(monthlyData.Data.details[i].total_size,true)
		})*/
		if(monthlyData.hasOwnProperty('Data')){
			/*$.each(monthlyData.Data.details,function(i,v){
				monthlyData.Data.details[i].total_size = humanFileSize(monthlyData.Data.details[i].total_size,true)
			})*/
			return monthlyData.Data.details;	
		}
	}
	function getMonthlyDivisionsTypeData(month,division){
		year = $('#year_filter').val();
		var monthlyData = function() {
			var temp = null;
			url2 = base_url + "queryglobal/QueryGlobal";
	        data2 = {"query":"4EA4444894FDB6776A123656CA225FA20A4C44874EC14352E8A028F0FFE31F0552CB7CD8AA3A75DCB1A66534540A7E08C065CDBFC6D7462DA9CF5ED5D22DAB62F8E5ED0EE37FBEB08E971560CDCCF180CFFF17BDCA1A9BBB288B173ABE37D8EC2FF99DFC060D53C8161E6A3DD84712DD2B12D3C121150570D9B4460084C6A75F2929984C636C645EED8038D04B3FCDD103104C683530009548C3D7B57EB5F625BD23C0CA4550279E55C3A4E8AF261224B7E1435E06BF5D5D583229458E9D39B032EB035FA1FF8ADC529EB32BE09AE71220C6FA4E3048D66CA6B148D23BD8CD2927CE3288168F285C5D44C586742E457B976FB02D21C55BDAB095AD98423BBC255A192D31F32CB9BE3892CD70431FFC9870827B98D78AE0A2A5283E638E18B51445369CB81EFFC2F2C467E6379BB545132C5644B9C9CF2CCE376E9F70C1F3E6249E30F68933B1A1766D784F7B4D21378C1729EEFB466D7BB1EADA68A229CB0813BD143632593CDCD85E1F55E5DD0BA138B987DC89F4F621A40A3A0F78C6016143607262C738565982B062E9BA7BF1671E85DE1B3E8C2115F8C89A017D57B2908E7CF5B61836FA29B7EFD699F2AF900E5DEF6E73818F1BA19644CF0BA3E86C78917934535A054B9D13ED8078CB838964E4115C108EF75EE0399F3C26E66AC5ACB5BED2B109D6D49302B348BF04372229FD65C4A3B1016664CB5FCC5D4454F971254964EE22ED4198037EF1E90C3917097523EA6FE0B5CDD7348A93DBF30180646B5C6A3B1FE4A01F0CD6CAE051BADF9CA572C087B8F8833BAAA3938B5B45C76251316A3427EBA8791325BB223269E42A15A41736BC5904979CFBFE787E56B9D9EEA1590337B9D6872B7C651206ED13813C79504BAFB25D9841C294387BF5CE6D8D7C7D437347D8D25AB7D87297047218FF3105937849250774F9152DD366517937B81DE7185A28AC59C93C23696D1D778896077EE19B703AB77219380F7F8C1751E5514A47C6E111F48C8FB1B09CED81026E7799E7D8D8688650F58BDA3783D0FF05E59385AEFDE21A9A1E32A82E72050C2A405BB155FDDD2D00686C463E258AA902091F41D6933BA4863B97B7A2D19002F8F824D33C3D3346F67FF8ED07805C759336B9AE0309873CF08EBB0ABBE5A70506BDE7D42FEDAB29E26805E50326588F0FD2CD9BEE0748E59DED32476277081D6E176E3FD9E971EAF9FE31C3C12F057D88A4FF7B034069AF1B909C6F0D453C6CE41D4920679566EDFFC6BF850478E24093B04C137849D7A16F5B56610DA9AB143DE7EA4AAEEA1DBA90905C662DA83F9F52F7424FFF8C68A5841903D7B5B73FA8531BB669BFB4B799FA1B7F6DF326C3B06A8EE1D7F3BDA90CEEE059AE0E032DCC5D375574042271DF0E5D1283C6D0EE654E61DD2B92591FAE0C6B79CD7C05F98A9632F1A8DE84D5CFF94849A6A76F6E67D34CC2A79B1E84D82C9C",
                "params":[month,division,division,year,month,division]};
	        $.ajax({
	        	async: false,
	            url: url2,
	            data: data2,
	            type:'POST',
	            dataType:'JSON',
	            success: function(res) {
	            	temp = res;
	            	console.log(temp)
	            	//console.log("meron "+res);
	            }
	        });  
	        
			return temp;
		}();
		if(monthlyData.hasOwnProperty('Data')){
			/*$.each(monthlyData.Data.details,function(i,v){
				monthlyData.Data.details[i].total_size = humanFileSize(monthlyData.Data.details[i].total_size,true)
			})*/
			return monthlyData.Data.details;	
		}
	}
	function humanFileSize(bytes, si) {
	    var thresh = si ? 1000 : 1024;
	    if(Math.abs(bytes) < thresh) {
	        return bytes + ' B';
	    }
	    var units = si
	        ? ['KB','MB','GB','TB','PB','EB','ZB','YB']
	        : ['KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];
	    var u = -1;
	    do {
	        bytes /= thresh;
	        ++u;
	    } while(Math.abs(bytes) >= thresh && u < units.length - 1);
	    return bytes.toFixed(1)+' '+units[u];
	}


})