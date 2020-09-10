
var cours = document.getElementById('cours');
var plus = document.getElementById('plus');
var detail = document.getElementById('detail');
var choixx = '';
var check = false; //cette variable nous dira si l'user a fait un choix ou non
$('#plus').click(function(){ 
if(!cours.value){
  $('#detail').html('<span style="color:red;">Vous devez entrer un cours </span>');
  return false;
}else{
  let b = "../img/fancybox_loading.Gif";
  $('#detail').html('<center> <img src="'+ b +'"> </center>');
  let link = "prendre-branche?cours=";
  $.ajax({
    type: 'POST',
    dataType: 'json',
    async: true,
    url: link+cours.value,
    timeout: 100000,
 success: function(data) {
   var branche;
   var option ;
    $('#detail').html('');
    for(i=0; i<=data.length; i++){
      branche = data[i];
        var option = branche['branche'].split(',');
        
      $('#detail').append(
       'choisissez le(s) domaines que vous vouliez travailler en <b>'+branche['cours']+'</b> <hr />'
      );
      for(j=0; j<option.length;j++){
        $('#detail').append(
         '<br /><input type="checkbox" name="op" id="i'+j+'" value="'+option[j]+'" /> <label for="i'+j+'">'+ option[j]+ '</label><br />'+
         ''
         );
      
        
      }

      $('#send').html('<button class="btn btn-primary" type="button" id="hop">Suivant <span class="fa fa-send"></span></button>');
      $('#hop').click(function(){
      var choix = document.getElementsByName('op');
      for(x=0;x<choix.length;x++){
        if(choix[x].checked === true){
          //alert('vous avez choisir: '+choix[x].value);
          choixx += choix[x].value +',';
          check = true;
        }
        
      }
    });
      
     // plus.type='submit';
      // plus.textContent='nouveau choix ';
      plus.style.display='none';
      $('#plus').click(function(){
       // location.href='/traitement-cours/'+ option.length +'/';
       
     
      });

      $('#hop').click(function(){
        if(check == true){
           location.href='/traitement-cours/'+ choixx +'/'+ $('#cours').val();
        }else{ alert('faites un choix '); }
      });

     
     
    }
    
    
 },
 error:function(){
   $('#detail').html('<span style="color:red">Erreur survenue.<br /> La requette n\'a pas abouti</span>');
 }
});

}

});
