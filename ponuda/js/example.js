
// from http://www.mediacollege.com/internet/javascript/number/round.html
function roundNumber(number,decimals) {
  var newString;// The new rounded number
  decimals = Number(decimals);
  if (decimals < 1) {
    newString = (Math.round(number)).toString();
  } else {
    var numString = number.toString();
    if (numString.lastIndexOf(".") == -1) {// If there is no decimal point
      numString += ".";// give it one at the end
    }
    var cutoff = numString.lastIndexOf(".") + decimals;// The point at which to truncate the number
    var d1 = Number(numString.substring(cutoff,cutoff+1));// The value of the last decimal place that we'll end up with
    var d2 = Number(numString.substring(cutoff+1,cutoff+2));// The next decimal, after the last one we want
    if (d2 >= 5) {// Do we need to round up at all? If not, the string will just be truncated
      if (d1 == 9 && cutoff > 0) {// If the last digit is 9, find a new cutoff point
        while (cutoff > 0 && (d1 == 9 || isNaN(d1))) {
          if (d1 != ".") {
            cutoff -= 1;
            d1 = Number(numString.substring(cutoff,cutoff+1));
          } else {
            cutoff -= 1;
          }
        }
      }
      d1 += 1;
    } 
    if (d1 == 10) {
      numString = numString.substring(0, numString.lastIndexOf("."));
      var roundedNum = Number(numString) + 1;
      newString = roundedNum.toString() + '.';
    } else {
      newString = numString.substring(0,cutoff) + d1.toString();
    }
  }
  if (newString.lastIndexOf(".") == -1) {// Do this again, to the new string
    newString += ".";
  }
  var decs = (newString.substring(newString.lastIndexOf(".")+1)).length;
  for(var i=0;i<decimals-decs;i++) newString += "0";
  //var newNumber = Number(newString);// make it a number if you like
  return newString; // Output the result to the form field (change for your purposes)
}

function update_total() {
  var total = 0;
  $('.iznos').each(function(i){
    iznos = $(this).html();
    if (!isNaN(iznos)) total += Number(iznos);
  });

  total = roundNumber(total,2);

//x-((x/100)*(100-20))
  var total_rab = 0;
  $('.rabat_iznos').each(function(x){
    rabat = $(this).html();
    //document.getElementById('whereToPrint').innerHTML = rabat;
    if (!isNaN(rabat)) total_rab += Number(rabat);
  });

  total_rab = roundNumber(total_rab,2);

//total pdv

  var total_pdv = 0;
  $('.pdv_iznos').each(function(x){
    pdv = $(this).html();
    if (!isNaN(pdv)) total_pdv += Number(pdv);
  });

  total_pdv = roundNumber(total_pdv,2);


  //$('#subtotal').html(total);
  $('#zbir').html(total);
  $('#zbir_rabat').html(total_rab);
  $('#input_zbir_rabat').val(total_rab);
  $('#ukupan_pdv').html(total_pdv);
  $('#input_ukupan_pdv').val(total_pdv);
  
  update_balance();
}

function update_balance() {
  var due = Number($("#zbir").html()) - Number($("#zbir_rabat").html()) + Number($("#ukupan_pdv").html()) - Number($("#paid").val());
  due = roundNumber(due,2);
  $('.due').html(due);

  var zbir_minus_rabat = $("#zbir").html() - $("#zbir_rabat").html();
  zbir_minus_rabat = roundNumber(zbir_minus_rabat,2);
  $('#zbir_minus_rabat').html(zbir_minus_rabat);

  var ukupna_vrednost_sa_pdv = Number($("#zbir").html()) - Number($("#zbir_rabat").html()) + Number($("#ukupan_pdv").html());
  ukupna_vrednost_sa_pdv = roundNumber(ukupna_vrednost_sa_pdv,2);
  $('#ukupna_vrednost_sa_pdv').html(ukupna_vrednost_sa_pdv);
  $('#input_ukupna_vrednost_sa_pdv').val(ukupna_vrednost_sa_pdv);
}

function update_iznos() {
  //red iznos
  var row = $(this).parents('.item-row');
  var iznos = row.find('.cost').val() * row.find('.qty').val();
  iznos = roundNumber(iznos,2);
  isNaN(iznos) ? row.find('.iznos').html("N/A") : row.find('.iznos').html(iznos);
  //red rabat
  var row_rab = $(this).parents('.item-row');

  //var rabat = (row_rab.find('.cost').val() * row_rab.find('.qty').val())-(((row_rab.find('.cost').val() * row_rab.find('.qty').val())/100)*(100-row_rab.find('.rabat').val()));
  //var rabat = (row_rab.find('.cost').val() * row_rab.find('.qty').val())-(((row_rab.find('.cost').val() * row_rab.find('.qty').val())/100)*(100-row_rab.find('.rabat').val()));


 if(typeof row_rab.find('.rabat').val() != 'undefined'){
  var rabat_rabat = row_rab.find('.rabat').val();
 }
 else{
  var rabat_rabat=0;
 }

 var cost_rabat = row_rab.find('.cost').val();
 var qty_rabat = row_rab.find('.qty').val();
 //var test=(440*1)-(((440*1)/100)*(100-0));roundNumber

 //var rabat = roundNumber(cost_rabat * qty_rabat)-roundNumber(((cost_rabat * qty_rabat)/100)*(100-rabat_rabat));
 
 //var rabat = (cost_rabat * qty_rabat)-(((cost_rabat * qty_rabat)/100)*(100-rabat_rabat));
 
 //var rabat = roundNumber((cost_rabat * qty_rabat),2)-(roundNumber(((cost_rabat * qty_rabat)/100),2)*roundNumber((100-rabat_rabat),2));
 
 var cost_x_qty_d=cost_rabat * qty_rabat;
 var cost_x_qty_d_100=(cost_rabat * qty_rabat)/100;

 var b100_m_rabat=100-rabat_rabat;
 
 var rabat_pro2=cost_x_qty_d_100*b100_m_rabat;
 var rabat_pro3=roundNumber(rabat_pro2,2);
 
 
 var rabat = (cost_rabat * qty_rabat)-rabat_pro3;


  rabat = roundNumber(rabat,2);
  isNaN(rabat) ? row_rab.find('.rabat_iznos').html("N/A") : row_rab.find('.rabat_iznos').html(rabat);
  //red pdv (cena_r*kolicina-rabat)/100*pdv

  var row_pdv = $(this).parents('.item-row');
  var pdv = ((row_pdv.find('.cost').val() * row_pdv.find('.qty').val()) - rabat)/100 * row_rab.find('.pdv').val();
  pdv = roundNumber(pdv,2);
  isNaN(pdv) ? row_pdv.find('.pdv_iznos').html("N/A") : row_pdv.find('.pdv_iznos').html(pdv);

  update_total();
}

function bind_n() {
  $(".cost").blur(update_iznos);
  $(".qty").blur(update_iznos);
  $(".rabat").blur(update_iznos);
  $(".pdv").blur(update_iznos);

  var avans = $('#paid').val();
    if (avans == 0) {
    $(".avans").addClass("print_hide");
  }
}



$(document).ready(function() {

  $(".cost").each(update_iznos);


  bind_n();

  $('input').click(function(){
    $(this).select();
  });

  $("#paid").blur(update_balance);
   
  $("#addrow").click(function(){
    $(".item-row:last").after('<tr class="item-row">\
      <td class="redni_br"></td>\
      <td class="item-name"><div class="delete-wpr"><textarea name="ime_rob[]" class="ime_robe">Prajci tovljenici</textarea><a class="delete" href="javascript:;" title="Ukloni">X</a></div></td>\
      <td class="j_m"><textarea name="jedinica_m[]" class="j_m">kom</textarea></td>\
      <td><textarea class="qty" name="kolicina[]">1</textarea></td>\
      <td><textarea class="cost" name="cena[]">1</textarea></td>\
      <td><span class="iznos">0</span></td>\
      <td><textarea class="rabat" name="rabat[]">0</textarea></td>\
      <td><span class="rabat_iznos">0.00</span></td>\
      <td><textarea class="pdv" name="pdv[]">20</textarea></td>\
      <td><span class="pdv_iznos">0.00</span></td>\
    </tr>');
    if ($(".delete").length > 0) $(".delete").show();
    bind_n();
    $('.item-row').each(function (i) {
      $("td:first", this).html(i+1);
    });
  });
  
  $(".delete").live('click',function(){
    $(this).parents('.item-row').remove();
    update_total();
    if ($(".delete").length < 2) $(".delete").hide();
  });
  
  
  //$("#date").val(print_today());


 $('.item-row').each(function (i) {
   $("td:first", this).html(i+1);
});

 //$('#firma option:selected').bind_n('click', function() {
 //   var comments = $('#partner');
 //   comments.val(comments.val() + $(this).attr('label'));
//});

$("#dugme_ubaci").click(function(){
    var selected = $("#firma").val();

   // $(':input[value="'+selected+'"]')

    var partner_text = $('option[value="'+selected+'"]').attr('label');


    //var partner_text = $("#start").find('.myClass').val();

    $("#partner").text(partner_text);
});



});
