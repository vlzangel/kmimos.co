//Track el profile


<script>
mixpanel.identify();
var distinct_ID = mixpanel.get_distinct_id();
sidebarList= document.getElementsByTagName("ul")[7];
verificarCuidador= sidebarList.getElementsByTagName("li")[5];

if (verificarCuidador.innerText == 'CUIDADOR') {
  mixpanel.people.set({ 'TipoDeUsuario': 'Cuidador' });
} else {
    mixpanel.people.set({ 'TipoDeUsuario': 'Cliente' });
}



perfil=document.getElementsByClassName('input');

for (i = 0; i<perfil.length; i++){

if (i == 7){
mixpanel.people.set({ $email: perfil[i].value });

}
else {
mixpanel.people.set({ perfil[i].name: perfil[i].value });
}
}

if( document.getElementById('pf-ajax-profileupdate-button').onclick = ActualizarPerfil() {

  perfil=document.getElementsByClassName('input');

  for (i = 0; i<perfil.length; i++){

    if (i == 7){
    mixpanel.people.set({ $email: perfil[i].value });

    }

  mixpanel.people.set({ perfil[i].name: perfil[i].value });

  }


}



</script>
