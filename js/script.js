
var $ = jQuery.noConflict();

$('document').ready(function(){

console.log(sfs_js_data.timeout);
sliderInit(sfs_js_data.timeout);

function sliderInit(interval) {

    maxsliders=$('.sfs-container').find('.sfs-slide').length;

    slides_map = new Array();
    i = 0;

    $('.sfs-container').find('.sfs-slide').each(function() {
        
        slides_map[i] = this.getAttribute( "id" ).slice(10);
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

    $('.sfs-slide').removeClass('sfs-slide-active');
    $('.sfs-slide').removeClass('sfs-slide-old');
    $('#sfs_slide_'+slides_map[old]).addClass('sfs-slide-old');
    $('#sfs_slide_'+slides_map[current]).addClass('sfs-slide-active');

    return current;

}

});
