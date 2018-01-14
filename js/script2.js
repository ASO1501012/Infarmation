//雲ふわふわ

window.onload = function(){
    animateCloud();
    moveranking();
}
function animateCloud(){
    var y=0,sw=1;
    setInterval(function(){
        y = Math.floor( Math.random() * 10 )+1 * sw;
        $(".movecloud").animate({
            "top": y+"px"
        },1500);

        sw *= -1;
    },2000);
}

//ドラッグできるようになる
$(function() {
    $('.movecloud').draggable({
        start: function(event,ui){
            $(this).stop().animate();
        },
        drag: function(){
            $(this).stop().animate();
            $(this).clearQueue();
        },
        stop: function(){
            animateCloud();
            $(this).clearQueue();
        },
        dragend: function(){
        },
        revert: true
    });
});
$(function() {
    $('.mypage').draggable({revert: true});
});


//ヘッダーが横スクロールできるようになる
$(window).on("scroll", function(){
    $("header").css("left", -$(window).scrollLeft());
});

function clickc(id){
    //test.innerHTML=id;
    $('html,body').animate({scrollTop:1360},600,'swing');
}
function clicka(){
    $('html,body').animate({scrollTop:745},300,'swing');
}



function moveranking() {
    var ranking = $(".a3").offset().top - $(window).height() +1490;
    // ScrollTopの位置が「bottombar」よりも値が小さければ、
    // 「#bottom-bar」を固定
     $(window).scroll(function () {
        if ($(window).scrollTop() < ranking) {
          $("#ranking").removeClass("abs");
          $("#ranking").addClass("fixed");
        // 大きければ、「#bottom-bar」の固定を解除
        } else {
          $("#ranking").removeClass("fixed");
          $("#ranking").addClass("abs");
        }
    });
}
