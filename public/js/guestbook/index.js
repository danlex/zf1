jQuery("#guestbook-list").jqGrid({
	url:'/guestbook/index-json', 
	datatype: "json", colNames:['No','Email', 'Comment', 'Actions'], 
	colModel:[ 
		{name:'id',index:'id', width:25, align:'right'}, 
		{name:'email',index:'email', width:150}, 
		{name:'comment',index:'comment', width:200},
		{name:'act', align:'center', width:75,
			formatter: function (cellvalue, options, rowObject) {
				var id = rowObject['id'];
				return '<button id="edit-guestbook-'+id+'" onclick="editGuestbook('+id+')">edit</button>';
			}
		}
	], 
	rowNum:10,
	rowList:[10,20,30],
	pager: '#guestbook-pager',
	sortname: 'id',
	viewrecords: true,
	sortorder: "desc",
	caption:"Guestbook"
}); 
jQuery("#guestbook-list").jqGrid('navGrid','#guestbook-pager',{edit:false,add:false,del:false});

function editGuestbook(id){
	location.href='/guestbook/edit/id/'+id;
}
