/* Filter New */
$(document).ready(function(){


    /* слайдер цен */

    $("#slider").slider({
        min: 0,
        max: 1000,
        values: [0,1000],
        range: true,
        stop: function(event, ui) {
            $("input#minCost").val($("#slider").slider("values",0));
            $("input#maxCost").val($("#slider").slider("values",1));

        },
        slide: function(event, ui){
            $("input#minCost").val($("#slider").slider("values",0));
            $("input#maxCost").val($("#slider").slider("values",1));
        }
    });

    $("input#minCost").change(function(){

        var value1=$("input#minCost").val();
        var value2=$("input#maxCost").val();

        if(parseInt(value1) > parseInt(value2)){
            value1 = value2;
            $("input#minCost").val(value1);
        }
        $("#slider").slider("values",0,value1);
    });


    $("input#maxCost").change(function(){

        var value1=$("input#minCost").val();
        var value2=$("input#maxCost").val();

        if (value2 > 1000) { value2 = 1000; $("input#maxCost").val(1000)}

        if(parseInt(value1) > parseInt(value2)){
            value2 = value1;
            $("input#maxCost").val(value2);
        }
        $("#slider").slider("values",1,value2);
    });

    $('.nstSlider').nstSlider({
        "left_grip_selector": ".filter-price__pin--min",
        "right_grip_selector": ".filter-price__pin--max",
        "value_bar_selector": ".filter-price__difference",
        "value_changed_callback": function(cause, leftValue, rightValue) {
            $('#min-cost').val(leftValue);
            $('#max-cost').val(rightValue);
        },
        "user_mouseup_callback": function(cause, leftValue, rightValue) {
            filter();
        },
    });


});

    function filter(){
        p_low = [];
        p_high = [];
        $('#min-cost').each(function(element) {
            p_low.push(this.value);
        });

        $('#max-cost').each(function(element) {
            p_high.push(this.value);
        });

        location = '<?php echo $action; ?>&p_low=' + p_low.join() + '&p_high='+p_high.join();
    };
function reset(){
    location = '<?php echo $action; ?>';
};

$("#scale-slider").slider({
        min: 10,
    max: 500,
range: true,
    values: ['<?php echo (isset($price_range[0])?$price_range[0]:0); ?>', '<?php echo (isset($price_range[1])?$price_range[1]:$price_range_max); ?>']
})