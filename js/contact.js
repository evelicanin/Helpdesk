$(document).ready(function(){
			$('#promijeni_serviser').click(function(e){
	
				
				/* declare the variables, var error is the variable that we use on the end
				to determine if there was an error or not */
				var error = false;
				var status = $('#status').val();
				var rok = $('#krajnji_rok').val();
				
				
				/* in the next section we do the checking by using VARIABLE.length
				where VARIABLE is the variable we are checking (like name, email),
				length is a javascript function to get the number of characters.
				And as you can see if the num of characters is 0 we set the error
				variable to true and show the name_error div with the fadeIn effect. 
				if it's not 0 then we fadeOut the div( that's if the div is shown and
				the error is fixed it fadesOut. 
				
				The only difference from these checks is the email checking, we have
				email.indexOf('@') which checks if there is @ in the email input field.
				This javascript function will return -1 if no occurence have been found.*/
								
				if(status == "3" && rok.length == 0){
					var error = true;
					$('#rok_error').fadeIn(500);
					e.preventDefault();
				}else{
					$('#rok_error').fadeOut(500);
				}				
				
			});    
		});