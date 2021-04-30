jQuery(document).ready(function(){
    
    "use strict";

    function showTooltip(x, y, contents) {
    jQuery('<div id="tooltip" class="tooltipflot">' + contents + '</div>').css( {
        position: 'absolute',
        display: 'none',
        top: y + 5,
        left: x + 5
    }).appendTo("body").fadeIn(200);
    }
    
    /*****SIMPLE CHART*****/

    if(window.sevendaysales !==null && window.sevendaysales.length > 0){
        window.sevenDaySalesArea = new Morris.Area({
                    element: 'chart',
                    resize: true,
                    data: window.sevendaysales,
                    xkey: 'date',
                    xLabelAngle: 60,
                    parseTime: false,
                    ykeys: ['total'],
                    labels: ['Sales '],
                    lineColors:[ '#03c3c4'],
                    fillOpacity: 0.3,
                    preUnits : '$ ',
                    pointStrokeColors:[ '#' + Math.random().toString(16).slice(2, 8).toUpperCase()],
                    hideHover: 'auto'
            });
    }

    if(window.sevendaysCustomer !==null && window.sevendaysCustomer.length > 0){
        window.sevenDaysCustomerArea = new Morris.Area({
                    element: 'line-chart',
                    resize: true,
                    data: window.sevendaysCustomer,
                    xkey: 'date',
                    xLabelAngle: 60,
                    parseTime: false,
                    ykeys: ['total'],
                    labels: ['Customer '],
                    lineColors:['#905dd1'],
                    fillOpacity: 0.3,
                    pointStrokeColors:[ '#' + Math.random().toString(16).slice(2, 8).toUpperCase()],
                    hideHover: 'auto'
            });
    }
    
    if(window.dailySummary){

        if(window.dailySummary.total_creditCardSales !== null){
            var total_c_sales = window.dailySummary.total_creditCardSales;
        }else{
            var total_c_sales = '0.00';
        }

        if(window.dailySummary.total_tax !== null){
            var s_total_tax = window.dailySummary.total_tax;
        }else{
            var s_total_tax = '0.00';
        }

        if(window.dailySummary.cash_pickup !== null){
            var s_cash_pickup = window.dailySummary.cash_pickup;
        }else{
            var s_cash_pickup = '0.00';
        }

        if(window.dailySummary.total_paidOut !== null){
            var s_total_paidOut = window.dailySummary.total_paidOut;
        }else{
            var s_total_paidOut = '0.00';
        }

        if(window.dailySummary.beg_balance !== null){
            var s_beg_balance = window.dailySummary.beg_balance;
        }else{
            var s_beg_balance = '0.00';
        }

        if(window.dailySummary.cash_added !== null){
            var s_cash_added = window.dailySummary.cash_added;
        }else{
            var s_cash_added = '0.00';
        }

        if(window.dailySummary.total_cashSales !== null){
            var s_total_cashSales = window.dailySummary.total_cashSales;
        }else{
            var s_total_cashSales = '0.00';
        }

        if(window.dailySummary.total_checkSales !== null){
            var s_total_checkSales = window.dailySummary.total_checkSales;
        }else{
            var s_total_checkSales = '0.00';
        }

        if(window.dailySummary.total_debitSales !== null){
            var s_total_debitSales = window.dailySummary.total_debitSales;
        }else{
            var s_total_debitSales = '0.00';
        }

        if(window.dailySummary.total_discount !== null){
            var s_total_discount = window.dailySummary.total_discount;
        }else{
            var s_total_discount = '0.00';
        }

        if(window.dailySummary.total_ebtSales !== null){
            var s_total_ebtSales = window.dailySummary.total_ebtSales;
        }else{
            var s_total_ebtSales = '0.00';
        }

        if(window.dailySummary.total_giftSales !== null){
            var s_total_giftSales = window.dailySummary.total_giftSales;
        }else{
            var s_total_giftSales = '0.00';
        }

        if(window.dailySummary.total_nonTaxableSales !== null){
            var s_total_nonTaxableSales = window.dailySummary.total_nonTaxableSales;
        }else{
            var s_total_nonTaxableSales = '0.00';
        }

        if(window.dailySummary.total_returns !== null){
            var s_total_returns = window.dailySummary.total_returns;
        }else{
            var s_total_returns = '0.00';
        }

        if(window.dailySummary.total_taxableSales !== null){
            var s_total_taxableSales = window.dailySummary.total_taxableSales;
        }else{
            var s_total_taxableSales = '0.00';
        }

        var donut = new Morris.Donut({
                    element: 'sales-chart',
                    resize: true,
                    parseTime: false,
                    preUnits : '$ ',
                    colors: ["#80ff80", "#dfff80","#ff9f80","#cc99ff","#ff80ff","#ff8c66"," #dda8bb","#a3e4d7","#f0b27a","#85c1e9","#f9e79f","#45b39d ","#7fb3d5","#d4ac0d","#bfc9ca","#f5b041"],
                    data: [
                        {label: "Credit Card sale", value: total_c_sales},
                        {label: "Tax Amount", value: s_total_tax},
                        {label: "Cash Pickup", value: s_cash_pickup},
                        {label: "Paid Out", value: s_total_paidOut},
                        {label: "Opening Balance", value: s_beg_balance},
                        {label: "Cash Added", value: s_cash_added},
                        {label: "Cash Sales", value: s_total_cashSales},
                        {label: "Check Sales", value: s_total_checkSales},
                        {label: "Debit Sales", value: s_total_debitSales},
                        {label: "Discount", value: s_total_discount},
                        {label: "EBT Sales", value: s_total_ebtSales},
                        {label: "Gift Sales", value: s_total_giftSales},
                        {label: "Nontaxable Sales", value: s_total_nonTaxableSales},
                        {label: "Returns", value: s_total_returns},
                        {label: "TaxableSale", value: s_total_taxableSales}
                    ],
                    formatter: function (value, data) { return  '$ ' + value; },
                    hideHover: 'auto'
                });
                donut.options.data.forEach(function(label, i) {
                    var legendItem = $('<span></span>').text( label['label'] + " ( $ " +label['value'] + " )" ).prepend('<br><span>&nbsp;</span>');
                    legendItem.find('span')
                      .css('backgroundColor', donut.options.colors[i])
                      .css('width', '20px')
                      .css('display', 'inline-block')
                      .css('margin', '3px')
                      .css('vertical-align', 'middle');
                    $('#legend').append(legendItem)
                });
    }
    
    if(window.topItem !==null && window.topItem.length > 0){
        window.topFiveProductBar = new Morris.Bar({
                    element: 'item-chart',
                    resize: true,
                    data: window.topItem,
                    xkey: 'Item',
                    xLabelAngle: 30,
                    ykeys: ['Quantity'],
                    labels: ['Item'],
                    barColors: ['#ffcc99'],
                    opacity: 0.3,
                    hideHover: 'auto'
            });
    }
    
    if(window.topCategory !==null && window.topCategory.length > 0){
        window.topFiveCategoryBar = new Morris.Bar({
                    element: 'topFiveCategory',
                    resize: true,
                    data: window.topCategory,
                    xkey: 'category',
                    xLabelAngle: 30,
                    ykeys: ['total'],
                    labels: ['category '],
                    barColors: ['#bfff80'],
                    opacity: 0.3,
                    hideHover: 'auto'
            });
    }

    if(window.customer !==null && window.customer.length > 0){
        window.cutomerFlow = new Morris.Area({
                    element: 'cust-chart',
                    resize: true,
                    data: window.customer,
                    xkey: 'date',
                    xLabelAngle: 60,
                    parseTime: false,
                    ykeys: ['total'],
                    labels: ['Customer'],
                    lineColors:[ '#3c8dbc'],
                    fillOpacity: 0.3,
                    pointStrokeColors:[ '#' + Math.random().toString(16).slice(2, 8).toUpperCase()],
                    hideHover: 'auto'
            });
    }

    setTimeout(function(){
        jQuery('#bar-chart svg').css('height','350px'); 
        jQuery('#item-chart svg').css('height','350px'); 
    }, 2000);
     
    
});