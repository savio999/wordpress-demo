(function($){
	$('document').ready(function(){
		$('.print_pdf').on('click',function(e){
			var btn_id = $(this).attr('id');
			var arr = btn_id.indexOf('_');
			var id = btn_id.substr(0,arr);
			var adminurl = ppdf_load_ajax_url.admin_ajax_url;
			$.ajax({
				url:adminurl,
				type:'POST',
				async:false,
				data:{
					action:'order_data',
					orderID:id
				},
				dataType:'json',
				success: function(data){
					generatePDF(data);
				}
			})
			e.stopPropagation();
			e.preventDefault();
		});
	});

	function generatePDF(data){
                var seller_name = data.seller.name;
		var seller_address = data.seller.address;
                var seller_pincode = data.seller.pincode;
		var seller_country = data.seller.country;
                var purchaser_name = data.purchaser.name;
		var purchaser_address = data.purchaser.address;
                var purchaser_pincode = data.purchaser.postcode;
		var purchaser_country = data.purchaser.country;
                var products = data.product;
                var products_arr = [];
                
                if(products){
                    for(var i=0; i<products.length; i++){
                        products_arr[i] = [products[i]['qty'],
                                        products[i]['name'],
                                        products[i]['total'],
                                        ''
                                        ];
                    }
                }
                
                
                
                if(!seller_name){
			seller_name_display = {text:'Seller\'s Name',style:'label'};
		}else{
			seller_name_display = {text:seller_name};
		}
                
		if(!seller_address){
			seller_addr_display = {text:'Address',style:'label'};
		}else{
			seller_addr_display = {text:seller_address};
		}
                
                if(!seller_pincode){
			seller_pin_display = {text:'Post Code and place',style:'label'};
		}else{
			seller_pin_display = {text:seller_pincode};
		}
                
		if(!seller_country){
			seller_country_display = {text:'Country',style:'label'};
		}else{
			seller_country_display = {text:seller_country};
		}
                
                if(!purchaser_name){
			purchaser_name_display = {text:'Purchaser\'s Name',style:'label'};
		}else{
			purchaser_name_display = {text:purchaser_name};
		}
                
		if(!purchaser_address){
			purchaser_addr_display = {text:'Address in home country/on Spitzbergen or Jan Mayen',style:'label'};
		}else{
			purchaser_addr_display = {text:purchaser_address};
		}
                
                if(!purchaser_pincode){
			purchaser_pin_display = {text:'Post Code and place',style:'label'};
		}else{
			purchaser_pin_display = {text:purchaser_pincode};
		}
                
		if(!purchaser_country){
			purchaser_country_display = {text:'Country',style:'label'};
		}else{
			purchaser_country_display = {text:purchaser_country};
		}
                
                products_x = [];
                products_x.push([{text: 'Quantity', style: 'tableHeader', alignment: 'center'}, {text:'Type of goods', style: 'tableHeader', alignment: 'center'}, {text:'Retail price NOK ex. Value Added Tax', style: 'tableHeader', alignment: 'center'},{text:'Value Added Tax NOK', style: 'tableHeader', alignment: 'center'}]);
               
                if(products){                    
                    for(var i=0; i<products.length; i++){
                     products_x.push([{text: products[i]['qty']}, {text:products[i]['name']}, {text:products[i]['total']},{text:''}]);
                    }
                   
                }
                
                //console.log(products_x);
		
		var docDefinition = {
				content:[
					{
						table:{
							body:[
								[{
							        image: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGEAAACVCAYAAABIBZSsAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAClXSURBVHhe7d0J1G5T/QfwuxaVkqKBUqJBZIw0Z6qQocyzEq6iNEgTkaFuhVAaDEVxhTQYGgxlSBlShCaNKqVEEypDnP/67LW+1nbWeYd73+d53uf1f961fvc8zzn77LP37/ub9z7PnfXb3/62QTfeeOPQ0k033dT88Ic/bL7xjW80X/rSl5ozzzyzufrqq8v5rvYzjYYehF//+tfN5Zdf3nzta19rLr300sL88847rwDygx/8oPnFL37Red9MoqEG4Xe/+13zs5/9rDnllFOac889t5z7+9//XoABBGCuvfba0q5970yiodcEkv7FL36x+fznP99ceeWVZayXXHJJAebrX/9685Of/GQEQr/J2K6//vrmW9/6VmE+0/TNb36zufDCC5uf/vSnRSu67ptJNPQgkHKMxnjSf+KJJzaf+cxnmvPPP7/5zW9+M+O1AA29T2COSP1Xv/rVYpIA8LnPfa758pe/XBz1z3/+85E56idhLhPECf/4xz8u2vCVr3ylueqqq5of/ehHxUR973vfG2pNngwNNQjMEIm/4oormj//+c/FBMkRREy+A0CENNO1YahBYIpOOumkIv0XXHBB84UvfKE57bTTSlREC3yeO3du0ZLf//73nX3MBBo4CCQWw7rI9YwHcbwnnHBC88lPfrI5+eSTS6jKNzgC5+ijjy7HaELu0U9X/2gYNWYgIJg4wiDSjWlMSsh38f4111xT7D2SGTtH2gHBCWt7ww03lONFF11UHDVQrrvuugfd67N79Vs/y2fPT1Q1LID0HQR9i+dltuo/l112WYl2mBPEzp9zzjnNWWed1Vx88cXlmnOcseuYfMwxx5QyBQbefPPN5Xj22WeXSImp+va3v13au8/9AOI7tHGNKdOX855vHBw7UPotgJOhvoNASjEAEzFKhMOZYhDGn3HGGc2nPvWp4oAxRT2IIwaIUoW27P5HP/rRYnqcowEf+9jHynf96FcU5T4aBHAgaANEnwGtL8/X3meg0JiucQ+S+goCtSedJFGmi7H5jhEYCgjM5FwxUZX0O9/5TiEmyBGzPv3pTxepJ8WOfARwMT/ttcNs0v+HP/yhaIrvnuN52nu2dkogAHHfL3/5y87xD4r6CoLJMQ8mbOImTdJJX+w7qcUo5zEPU5kKoGEQ0EjtUUcdVa4rXztHG9yHqdp5hj4ATbtolfs8AzGJSBtax3cwTdo7P53+oe8gkEDSnXifhCZS0QYIp59+ejFXNOLtb39786Y3vanZcsstm/XWW6952cte1jz/+c9vVl555eawww4rjDvwwAObF73oRc0aa6zRrLnmmqXd1ltv3ey5557NvvvuW6Im0i+KipR7nmf/8Y9/LGASCKaMkADsIQmCSX3/+98vZoPUivGZHpHLr371qyLpyg/77LNPYZ4IiPnA0Mc97nHNox71qEKPfOQjm0c84hHN05/+9Ob9739/6eOd73xn88xnPrNZaKGFyvW0XWKJJZpXv/rVxZGrMe26666lLXBJvnnyGcYCeLUoWuNa1xwGRX0DgeTFsfp8yy23FNOB2Yceemjzmte8pllqqaWaJz/5yUWy//a3vzX/+c9/CkirrrpqYe6iiy7aLLLIIs2jH/3ooh38xr///e/i7DH4MY95TLnuiF7+8pcXTbn77rubv/zlL83BBx/cPPzhD2+e9rSnNZtuumlzxBFHNJ/97GdLG88zLprKJCW/mA7qGwhU/7vf/W6ZIIZgHgnEKIyP9D772c8uZubee+9t/ve//xVGv/CFL2wWW2yxwlhAPPGJT2z23nvvYlruv//+Yj6Asvjiiz8AxBOe8IRm4403LjZfG8/TrzYLL7xw0Rr9vOIVryjCceeddxZt5U84+4ekOSJl7D3p1781AepPEt/61rcW5m+//fbFbjvHX9x2223lM1NBamnKRhttVLSHiQGoftlzJkZou/766zerrLJK8+EPf7j0JZz961//WkzMkUceWUocW2yxRQGev2CmmEe5BtPEydMs/XbNYxDUd8fM4TIxGPOvf/2rmApRDUfKB/hOKv/0pz+VpUtH52699dbC4I985CPFmd5+++0FJOeR7xjn+uzZs0sERPpp3T//+c9S4PvHP/7R3HPPPc3xxx9fwCTxTJ6xMEciKsC69yEbHSGRiPAUAzATg0VKTIcohgnCXE4cM4Sn9913X7lX5HPAAQeU+/gUpor0klyMZMff9773Ndtuu225D5jakHLAA5RpkuittdZaJe/473//W56jH3kDDZxOf4D6CgJJxVzSJhI66KCDSoSD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',
							        width: 50
								},
								{
									text: 'Exportation of goods by persons living abroad or persons with residence on Spitzbergen or Jan Mayen and purchased during temporary stay in Norway.',
									style: 'header'
								}
								]
							]
						},
						layout:{
							fillColor:'#d3d6d8',
							hLineWidth: function (i, node) {
								return 0;
							},
							vLineWidth: function (i, node) {
								return 0;
							},
							padding: function(i, node) {return 2;}
						},
						style:'head_table'
					},
					{
						table:{
							body:[
								['This form is to be used when a person living abroad or a person with residence on Spitzbergen or Jan Mayen requests a refund of the Value Added Tax from the seller for goods which are to be taken out of Norway in travellers’ luggage. The purchaser is to send the form, authenticated  by  Norwgian back to the seller. he form is for use by residents of countries other than Denmark, Finland and Sweden only, as well as for sales to persons with on Spitzbergen or Jan Mayen.',
								'A refund of the Value Added Tax is granted for sales to persons living abroad if the individual invoice amounts to a minimum of NOK 250 excluding VAT and if the exportation of the goods takes within a month of purchase. On sales to persons with residence on Spitzbergen or Jan Mayen a  refund of VAT is granted in accordance with the same provisions the price of the individual item amounts to a minimum of NOK 1.000 excluding VAT.   A group of goods normally forming an unit is deemed  residence to be an item.']
							]
						},
						layout:{
							fillColor:'#f7f7f7',
							hLineWidth: function (i, node) {
								return (i === 0 || i === node.table.body.length) ? 2 : 1;
							},
							vLineWidth: function (i, node) {
								return (i === 0 || i === node.table.widths.length) ? 2 : 0;
							},
							paddingTop: function(i, node) {return 2;}
						},
						style:'desc_table'
					},
					{
						table:{
							widths:['100%'],
							heights:[15],
							body:[
								[{
									text: ''
									,style: 'blank'
								}]
							]
						},
						layout:{
							fillColor:'#d3d6d8',
							hLineWidth: function (i, node) {
								return 0;
							},
							vLineWidth: function (i, node) {
								return  0;
							}
						},
						style:'desc_table'
					},
					{
						table:{
							widths:['50%','*'],
							heights:[35, 35, 35, 35],
							body:[
								[seller_name_display,purchaser_name_display],
								[seller_addr_display,purchaser_addr_display],
								[seller_pin_display,purchaser_pin_display],
								[seller_country_display,purchaser_country_display]
							]
						},
						layout:{
							hLineWidth: function (i, node) {
								return (i === 0 || i === node.table.body.length) ? 2 : 1;
							},
							vLineWidth: function (i, node) {
								return (i === 0 || i === node.table.widths.length) ? 2 : 1;
							},
						},
						style:'desc_table'
					},
					{
						table:{
							widths:['100%'],
							heights:[15],
							body:[
								[{
									text: ''
									,style: 'blank'
								}]
							]
						},
						layout:{
							fillColor:'#d3d6d8',
							hLineWidth: function (i, node) {
								return 0;
							},
							vLineWidth: function (i, node) {
								return  0;
							}
						},
						style:'desc_table'
					},
					{
						table:{
							widths:['10%','50%','20%','20%'],
							body:products_x
               
                                                            
						},
						layout:{
							hLineWidth: function (i, node) {
								return (i === 0 || i === node.table.body.length) ? 2 : 1;
							},
							vLineWidth: function (i, node) {
								return (i === 0 || i === node.table.widths.length) ? 2 : 1;
							},
						},
						style:'desc_table'
					},
					{
						table:{
							widths:['100%'],
							heights:[15],
							body:[
								[{
									text: ''
									,style: 'blank'
								}]
							]
						},
						layout:{
							fillColor:'#d3d6d8',
							hLineWidth: function (i, node) {
								return 0;
							},
							vLineWidth: function (i, node) {
								return  0;
							}
						},
						style:'desc_table'
					},
						{
						table:{
							widths:['50%','50%'],
							heights:[35],
							body:[
								[{text: 'Seller\'s Signature and Stamp',style:'label'}, {text:'Invoice No. and Date',style:'label'}]
								]
						},
						layout:{
							hLineWidth: function (i, node) {
								return (i === 0 || i === node.table.body.length) ? 2 : 1;
							},
							vLineWidth: function (i, node) {
								return (i === 0 || i === node.table.widths.length) ? 2 : 1;
							},
						},
						style:'desc_table'
					},
					{
						table:{
							widths:['100%'],
							heights:[15],
							body:[
								[{
									text: ''
									,style: 'blank'
								}]
							]
						},
						layout:{
							fillColor:'#d3d6d8',
							hLineWidth: function (i, node) {
								return 0;
							},
							vLineWidth: function (i, node) {
								return  0;
							}
						},
						style:'desc_table'
					},
					{
						table:{
							widths:['100%'],
							body:[
								[{text: 'The purchaser may not claim any refund of Value Added Tax without authentication from the Norwegian Customs. \nThe purchaser must therefore present this declaration to the Customs on departure from Norway, and thereafter return the declaration by post to the seller who will refund the Value Added Tax.', italics:true}]
								]
						},
						layout:{
							fillColor:'#f7f7f7',
							hLineWidth: function (i, node) {
								return (i === 0 || i === node.table.body.length) ? 2 : 1;
							},
							vLineWidth: function (i, node) {
								return (i === 0 || i === node.table.widths.length) ? 2 : 1;
							},
						},
						style:'desc_table'
					},
					{
						pageBreak:'after',
						table:{
							widths:['100%'],
							heights:[15],
							body:[
								[{
									text: ''
									,style: 'blank'
								}]
							]
						},
						layout:{
							fillColor:'#d3d6d8',
							hLineWidth: function (i, node) {
								return 0;
							},
							vLineWidth: function (i, node) {
								return  0;
							}
						},
						style:'desc_table'
					},
					{
						table:{
							widths:['25%', '25%', '50%'],
							heights:[15, 40, 40],
							body:[
									[{text: 'For official use only',style:'headings',colSpan:3},'',''],
									[{text:'The above-mentioned goods have today been taken out of Norway / taken to Spitzbergen or Jan Mayen by the purchaser.',style:'offical_stat1',rowSpan:2},{text:'Stamp',style:'label',rowSpan:2},{text:'Place and date',style:'label'}],
									[{},{},{text:'Signature of Customs officer',style:'label'}]
								]
						},
						layout:{
							hLineWidth: function (i, node) {
								return (i === 0 || i === node.table.body.length) ? 2 : 1;
							},
							vLineWidth: function (i, node) {
								return (i === 0 || i === node.table.widths.length) ? 2 : 1;
							},
						},
						style:'desc_table'
					},
					{
						text:'RD-0032E (04-2017) Electronic version'
					}
				],
				styles:{
					header:{
						fontSize:15,
						bold:true
					},
					desc_table:{
						margin:[0,0,0,0]
					},
					head_table:{
						margin:[0,0,0,0]
					},
					blank:{
						color:'#f7f7f7'
					},
					tableHeader:{
						fillColor:'#d3d6d8'
					},
					label:{
						fontSize:10
					},
					headings:{
						bold:true,
						fontSize:13,
						fillColor:'#d3d6d8'
					},
					offical_stat1:{
						padding:[2,10,10,10,10],
						fillColor:'#f7f7f7'
					}
				}
			};
			pdfMake.createPdf(docDefinition).download('order_options.pdf');
	}
}(jQuery));

