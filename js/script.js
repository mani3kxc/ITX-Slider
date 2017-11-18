
var $ = jQuery.noConflict();

$('document').ready(function(){

var debug = 1;
var current =0;
var old = 0;
var interval = itxsl_js_data.timeout;
var maininterval = 0;
var maxsliders = 0;

slides_map = new Array();

        if(debug==1)
        {
            console.log(itxsl_js_data.timeout);
        }

sliderInit();
var maininterval = SlideshowOn();

function sliderInit() {

    maxsliders=$('.itxsl-container').find('.itxsl-slide').length;

   
    i = 0;

    $('#prev-button').on('click', function() {
        current=ButtonPrev(current, maxsliders);
    });

    $('#next-button').on('click', function() {
        current=SliderNext(current, maxsliders,true);
    });

    $('.itxsl-container').find('.itxsl-slide').each(function() {
        
        slides_map[i] = this.getAttribute( "id" ).slice(12);
        i++;

    });
    maxsliders = i;

    if(debug==1)
    {
    console.log(maxsliders);
    }

    

 
}

function SlideshowOn() {

    current=SliderNext(maxsliders-1,maxsliders, false);    

    return setInterval( function() { 
        current=SliderNext(current,maxsliders, false); 
    }, interval );


}

function HandleSlides(current, old) {

    console.log("Hcurrent " + current + " -> " + slides_map[current]);
    console.log("Hold " + old + " -> " + slides_map[old]);

    $('.itxsl-slide').removeClass('itxsl-slide-active');
    $('.itxsl-slide').removeClass('itxsl-slide-old');
    $('.itxsl-dot').removeClass('itxsl-dot-active');
    $('#itxsl_slide_'+slides_map[old]).addClass('itxsl-slide-old');
    $('#itxsl_slide_'+slides_map[current]).addClass('itxsl-slide-active');
    $('#itxsl_dot_'+slides_map[current]).addClass('itxsl-dot-active');

  $('.itxsl-dot').each(function(index) {
        $(this).unbind();
    });

     $('.itxsl-dot:not(.itxsl-dot-active)').each(function(index) {
        $(this).on('click', function() {
            GoToSlider(this.getAttribute( "id" ).slice(10));
        });
    });

}

function SliderNext(current,maxsliders,button)
{
    if(button)
    {
        clearInterval(maininterval);
    }

    if(current<maxsliders-1)
    {
        old=current;
        current=current+1;
        if(debug==1)
        {
            console.log("current " + current + " -> " + slides_map[current]);
            console.log("old " + old + " -> " + slides_map[old]);
        }
    }
    else
    {        
        
        old=maxsliders-1;
        current=0;
        if(debug==1)
        {
            console.log("current " + current + " -> " + slides_map[current]);
            console.log("old " + old + " -> " + slides_map[old]);
        }
    }

    HandleSlides(current, old);

   

    return current;
}

function ButtonPrev(current,maxsliders)
{
      
    clearInterval(maininterval);

    if(current>0)
    {
        old=current;
        current=current-1;
        if(debug==1)
        {
            console.log("current " + current + " -> " + slides_map[current]);
            console.log("old " + old + " -> " + slides_map[old]);
        }
    }
    else
    {        
        
        old=0;
        current=maxsliders-1;
        if(debug==1)
        {
            console.log("current " + current + " -> " + slides_map[current]);
            console.log("old " + old + " -> " + slides_map[old]);
        }
    }

    HandleSlides(current, old);

    return current;
}

function GoToSlider(slide_id){

    clearInterval(maininterval);
    old = current;

    for (var i=0; i<slides_map.length; i++)
        if (slides_map[i] === slide_id)                    
            current = i; 


   HandleSlides(current, old);

    maininterval = setInterval( function() { 
        current=SliderNext(current,maxsliders,false); 
    }, interval );
    
}

});
