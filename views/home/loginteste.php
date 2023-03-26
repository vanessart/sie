<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <title>API Login Google TESTE</title>
        
        <script src="https://apis.google.com/js/platform.js" async defer></script>
        <meta name="google-signin-client_id" content="1031640452873-0qf23e4qdqrdcssqbkq3uj1mht6prfvp.apps.googleusercontent.com">
    </head>
    <body>
        <h2>API Login Google Teste</h2>
        
        <div class="g-signin2" data-onsuccess="onSignIn"></div>
    


        <p id="msg"></p>
        
        <script>
        function onSignIn(googleUser) {
            var profile = googleUser.getBasicProfile();
            //console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
            //console.log('Name: ' + profile.getName());
            //console.log('Image URL: ' + profile.getImageUrl());
            //console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
            
            var userID = profile.getId(); 
            var userName = profile.getName();
            var userPicture = profile.getImageUrl();
            var userEmail = profile.getEmail(); 
            var userToken = googleUser.getAuthResponse().id_token;
            
            console.log(userID);    
            
            if(userName != '') {
                
                var dados = {
                    userID:userID,
                    userEmail:userEmail,
                };
                
                $.ajax({
                    type: "POST",
                    url: 'https://portal.educ.net.br/ge/home/validaloginteste/',
                    data : dados,  
                    beforeSend: function (jqXHR, settings) {
                        console.log('beforeSend...');
                    },
                    success: function (data, textStatus, jqXHR) {
                        console.log('success...');
                        console.log(data);
                        $.ajax({
                            type: "POST",
                            url: 'https://portal.educ.net.br/ge/home/google/',
                            data : {idx:data},  
                            beforeSend: function (jqXHR, settings) {
                                console.log('beforeSend...');
                            },
                            success: function (data, textStatus, jqXHR) {
                                console.log('success II...');
                                console.log(data);
                            },
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        console.log('error...');              
                    },
                    complete: function (jqXHR, textStatus) {
                        console.log('complete...');
                    }
                });
            } else {
                var msg = 'Usuario não encontrado!';
                document.getElementById('msg').innerHTML = msg;
            }
          }

        </script>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        
    </body>
</html>
