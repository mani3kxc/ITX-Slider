
var $ = jQuery.noConflict();

$('document').ready(function(){

console.log(itxsl_js_data.timeout);
sliderInit(itxsl_js_data.timeout);

function sliderInit(interval) {

    maxsliders=$('.itxsl-container').find('.itxsl-slide').length;

    slides_map = new Array();
    i = 0;

    $('.itxsl-container').find('.itxsl-slide').each(function() {
        
        slides_map[i] = this.getAttribute( "id" ).slice(12);
        i++;

    });
    maxsliders = i;

    console.log(maxsliders);

    current=SliderNext(maxsliders-1,maxsliders); 
    setInterval( function() { 
        current=SliderNext(current,maxsliders); 
    }, interval );
}

function SliderNext(current,maxsliders)
{
    if(current<maxsliders-1)
    {
        old=current;
        current=current+1;
        console.log("current " + current + " -> " + slides_map[current]);
        console.log("old " + old + " -> " + slides_map[old]);
    }
    else
    {        
        
        old=maxsliders-1;
        current=0;
        console.log("current " + current + " -> " + slides_map[current]);
        console.log("old " + old + " -> " + slides_map[old]);
    }

    $('.itxsl-slide').removeClass('itxsl-slide-active');
    $('.itxsl-slide').removeClass('itxsl-slide-old');
    $('#itxsl_slide_'+slides_map[old]).addClass('itxsl-slide-old');
    $('#itxsl_slide_'+slides_map[current]).addClass('itxsl-slide-active');

    return current;

}

});
