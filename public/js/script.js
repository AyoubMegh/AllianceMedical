
$(document).ready(
    function(){

        setInterval(function(){
          var nombre_notif = document.getElementById('main_notif').innerText;
          $.ajax({   
            url: '/medecin/NombreNotif' ,
            type:'GET',
             success:function(data){
                $("#main_notif").html(data[0]);
                if(data[0]!=nombre_notif){
                    var audio = document.getElementById('play_notif');
                    audio.play();
                }
                var  result = [];
                for(var i in data[1]) result.push([data[1][i]]);
                var h6_tag = "<h6 class='dropdown-header'>Notifications </h6>"
                if(data[1].length==0){
                    var a_tags = "";
                }else if(data[1].length==1){
                    var a_tags = "<a class='dropdown-item d-flex align-items-center' href='/medecin/Notifications'>"+
                    "<div class='mr-3'>"+
                        "<div class='icon-circle bg-primary'>"+
                        "<i class='fas fa-file-alt text-white'></i>"+
                        "</div>"+
                    "</div>"+
                    "<div>"+
                    "<div class='small text-gray-500'>"+result[0][0]['created_at']+"</div>"+
                        "<span class='font-weight-bold'>"+result[0][0]['titre']+"</span>"+
                            "</div>"+
                    "</a>"+
                    "<a class='dropdown-item text-center small text-gray-500' href='/medecin/Notifications'>Voir tous les Notifications</a>";
                }else if(data[1].length==2){
                        var a_tags = "<a class='dropdown-item d-flex align-items-center' href='/medecin/Notifications'>"+
                            "<div class='mr-3'>"+
                                "<div class='icon-circle bg-primary'>"+
                                "<i class='fas fa-file-alt text-white'></i>"+
                                "</div>"+
                            "</div>"+
                            "<div>"+
                            "<div class='small text-gray-500'>"+result[0][0]['created_at']+"</div>"+
                                "<span class='font-weight-bold'>"+result[0][0]['titre']+"</span>"+
                                    "</div>"+
                            "</a>"+
                            "<a class='dropdown-item d-flex align-items-center' href='/medecin/Notifications'>"+
                                    "<div class='mr-3'>"+
                                        "<div class='icon-circle bg-primary'>"+
                                        "<i class='fas fa-file-alt text-white'></i>"+
                                        "</div>"+
                                    "</div>"+
                                    "<div>"+
                                    "<div class='small text-gray-500'>"+result[1][0]['created_at']+"</div>"+
                                        "<span class='font-weight-bold'>"+result[1][0]['titre']+"</span>"+
                                    "</div>"+
                            "</a>"+
                            "<a class='dropdown-item text-center small text-gray-500' href='/medecin/Notifications'>Voir tous les Notifications</a>";

                }else{
                    var a_tags ="<a class='dropdown-item d-flex align-items-center' href='/medecin/Notifications'>"+
                            "<div class='mr-3'>"+
                                "<div class='icon-circle bg-primary'>"+
                                "<i class='fas fa-file-alt text-white'></i>"+
                                "</div>"+
                            "</div>"+
                            "<div>"+
                            "<div class='small text-gray-500'>"+result[0][0]['created_at']+"</div>"+
                                "<span class='font-weight-bold'>"+result[0][0]['titre']+"</span>"+
                                    "</div>"+
                            "</a>"+
                            "<a class='dropdown-item d-flex align-items-center' href='/medecin/Notifications'>"+
                                    "<div class='mr-3'>"+
                                        "<div class='icon-circle bg-primary'>"+
                                        "<i class='fas fa-file-alt text-white'></i>"+
                                        "</div>"+
                                    "</div>"+
                                    "<div>"+
                                    "<div class='small text-gray-500'>"+result[1][0]['created_at']+"</div>"+
                                        "<span class='font-weight-bold'>"+result[1][0]['titre']+"</span>"+
                                    "</div>"+
                            "</a>"+
                            "<a class='dropdown-item d-flex align-items-center' href='/medecin/Notifications'>"+
                                    "<div class='mr-3'>"+
                                        "<div class='icon-circle bg-primary'>"+
                                        "<i class='fas fa-file-alt text-white'></i>"+
                                        "</div>"+
                                    "</div>"+
                                    "<div>"+
                                    "<div class='small text-gray-500'>"+result[2][0]['created_at']+"</div>"+
                                        "<span class='font-weight-bold'>"+result[2][0]['titre']+"</span>"+
                                    "</div>"+
                            "</a>"+
                            "<a class='dropdown-item text-center small text-gray-500' href='/medecin/Notifications'>Voir tous les Notifications</a>";

                }
                $("#show_notif").html(h6_tag+""+a_tags)
             }
          });
        },2000);
    }
);