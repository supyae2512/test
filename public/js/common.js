 $(function() {
    var path = window.location.origin;
     
     $(".add-cart").click(function () {
         var id = $(this).data('id');
         console.log('id' + id);
         $.ajax({
             url: path + '/add-cart',
             method: "GET",
             data: {'item_id': id},
             dataType: 'html',
             requestHeaders: {"User-Agent":"Mozilla 5.0 (Linux; X11)"},
             cache: false,
             beforeSend: function () {
             },
             success: function (data) {
                 $(this).css('color','red');
                 console.log(data);
                 
             }
         });
     });

    $("#token_btn").click(function() {
        var val = $("#token_btn span");
        var obj = $("#myToken");
        //console.log(obj.get(0).type);
        val.html(val.html() == 'Show' ? 'Hide' : 'Show');
        if (val.html() == 'Hide') {
            obj.attr("type", "text");
        } else {
            obj.attr("type", "password");
        }
    });

    $( '#generate_token' ).on('click',function()
    {
        $("#token_btn span").html('Hide');
        $("#myToken").attr("type", "text");

        $("#myToken").val( generateToken() );
    });

     $("#password_btn").click(function() {
         var val = $("#password_btn span");
         var obj = $("#myPassword");
         //console.log(obj.get(0).type);
         val.html(val.html() == 'Show' ? 'Hide' : 'Show');
         if (val.html() == 'Hide') {
             obj.attr("type", "text");
         } else {
             obj.attr("type", "password");
         }
     });

     $( '#generate_password' ).on('click',function()
     {
         $("#myPassword").attr("type", "text");
         $.ajax({
             url: path + '/admin/users/getPassword',
             type: "GET",
             data: {}, cache: false,
             success: function (data) {
                 $("#myPassword").val(data);
             }
         });
     });

//search product auto complete display
//     $('#search_keywords').autocomplete({
//         source: '/admin/getSuggestion/' + $(".suggest_type").val(),
//         minLength: 1,
//         select: function (e, ui) {
//             //var keyword = ui.item.label;
//             var id = ui.item.id;
//             switch ($(".suggest_type").val()) {
//                 case "users" :
//                     location.href = '/admin/users/detail/' + id;
//                     break;
//                 case "groups" :
//                     location.href = "/admin/groups/detail/" + id;
//                     break;
//             }
//         }
//     });

     $("#search_keywords").keyup(function (e) {
         if (e.keyCode === 13) {
             $("#search_data").click();
         }
     });

    $("#search_data").click(function () {
        var suggest_type = $(".suggest_type").val();
        var search_keyword = $("#search_keywords").val();
        if (search_keyword == '' || search_keyword == undefined) {
            alert('Please insert keyword to search !')
            return false;

        } else {
            location.href = path+'/getSearchData/'+suggest_type +'/'+search_keyword;
        }
    })

     $('.delete_post').click(function () {
         var url = $(this).data('url');
         var id = $(this).data('id');

         var result = confirm("Are you sure to delete ?");
         if (result) {
             location.href = window.location.origin + '/' + url + '/' + id;
         }
         return false;
     })
});


function generateToken() {
    //console.log(md5(Math.round(Math.random()*10) + 1, true));
    return md5(Math.round(Math.random()*10) + 1, true);

}
