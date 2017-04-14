$(document).ready(function(){
  $("div#bookmark_link").hover(function(){
    $(this).children().children(".bookmark_naziv").css({top: "0px"});
    $(this).children().children(".bookmark_opis").css({bottom: "0px"});
  }, function(){
    $(this).children().children(".bookmark_naziv").css({top: "-55px"});
    $(this).children().children(".bookmark_opis").css({bottom: "-35px"});
  });
  $("div.bookmark_row").hover(function(){
    $(this).css({background: "#222", "border-top": "5px solid whitesmoke", "border-bottom": "5px solid whitesmoke","border-left": "10px solid #8bcc12", "margin-left": "-10px", "margin-top": "-5px", "margin-bottom": "-10px"})
    $(this).children().children(".b_naziv").css({color: "whitesmoke"});
    $(this).children().children(".b_redni_broj").css({color: "whitesmoke"});
    $(this).children().children(".b_opis").css({color: "whitesmoke"});
    $(this).children(".x").css({visibility: "visible"});
  }, function(){
    $(this).css({background: "none", "border": "0", margin: 0});
    $(this).children().children(".b_naziv").css({color: "black"});
    $(this).children().children(".b_redni_broj").css({color: "black"});
    $(this).children().children(".b_opis").css({color: "black"});
    $(this).children(".x").css({visibility: "hidden"});
  });
});
$(window).resize(function() {
  var width =  $("#bookmark_link").width();
  $("#bookmark_link").css({height: width});
});
