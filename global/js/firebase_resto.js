/** MEJA **/
function statusMeja(dataResto)
{
	myApp.popup('.popup-meja');


    //fetch data
    var type  = 'resto';
    var resto = dataResto.data('resto');
    var kategori = 'standart';
    var table = type + '/' + resto + '/' + 'meja' + '/' + kategori;
    var ref = firebase.database().ref().child(table).orderByChild('id');
    var uid = firebase.auth().currentUser.providerData[0].uid;
    
    ref.on('value', function (snapshot) {
        var html = '';
        if (snapshot.hasChildren()) 
        {
            snapshot.forEach(function (child) {
                var obj = child.val();
                html += '<span class="'+(obj.status=='dipakai' ? (obj.user.id==uid ? 'meja-saya' : 'meja-terpakai') : '')+'">'
                        +'<input id="meja-'+obj.id+'" data-resto="'+  resto +'" name="meja" class="pilih-meja" value="'+obj.id+'" '+(obj.status=='dipakai' ? 'disabled=1' : '')+' type="radio">'
                        +'<label for="meja-'+obj.id+'" style="margin: 5px; padding: 30px 15px;">Meja '+obj.id+'</label>'
                    +'</span>';

            });
        }
        $('#statusMeja').html(html);
    });
    
}
function updateMeja(resto, id, kategori, selected, user)
{
    //MEJA
    var type  = 'resto';
    var table = type + '/' + resto + '/' + 'meja' + '/' + kategori;
    var user  = user ? user : ((firebase.auth().currentUser) ?  firebase.auth().currentUser.providerData[0] : null);
    
    var rowUser = {
        id : user ? user.uid : '-',
        nama : user ? user.displayName : '-',
        foto : user ? user.photoURL : '-',
    };

    var postData = {
        id: id,
        user: rowUser,
        status: selected ? 'dipakai' : 'tersedia',
    };

    return AddUpdateDate(table, postData);
}
function updateMejaUser(resto, id, kategori)
{
    var type  = 'resto';
    var table = type + '/' + resto + '/' + 'meja' + '/' + kategori;
    var uid   = firebase.auth().currentUser.providerData[0].uid;

    var ref = firebase.database().ref().child(table);
    ref.once('value', function(snapshot) {
        snapshot.forEach(function(userSnapshot) {
            var user = userSnapshot.val().user;
            if ( user.id == uid)
            {
                userSnapshot.ref.update({
                    status : "tersedia",
                    user : {
                        id : '-',
                        nama : 'Tamu',
                        foto : '-',
                    }
                });

                
            }
        });
    });
    updateMeja(resto, id, kategori, true);
}
/** EOF MEJA **/


function updatePesanan(elmn)
{
    var postData = {
        id: elmn.data('id'),
        nama: elmn.data('nama'),
        foto: elmn.data('foto'),
        url: elmn.data('url'),
        jumlah: elmn.closest('.shop_item_details').find('.qntyshop').val(),
        status: 'pesan',
    };
    
    //PESANAN
    var type  = 'resto';
    var resto = elmn.data('resto');
    var meja  = readCookie(resto+'-meja');
    var table = type + '/' + resto + '/' + 'pesanan' + '/' + meja;
    
    AddUpdateDate(table, postData);
}
//MAP
function initMap(target)
{
	 var map = new google.maps.Map(
        document.getElementById(target.attr('id')), {
          center: new google.maps.LatLng(target.data('lat'), target.data('lng')),
          zoom: 16,
          mapTypeId: google.maps.MapTypeId.ROADMAP
      });

      var marker = new google.maps.Marker({
            position: new google.maps.LatLng(target.data('lat'), target.data('lng')),
            map: map
      });

	  var infowindow = new google.maps.InfoWindow({
          content: target.data('info')
      });

	  infowindow.open(map, marker);

	  marker.addListener('click', function() {
          infowindow.open(map, marker);
      });

	  	//get location
		/*if (navigator.geolocation) {
			var currentPosition = null;
			navigator.geolocation.getCurrentPosition(function(position) {

				var directionsDisplay = new google.maps.DirectionsRenderer;
		        var directionsService = new google.maps.DirectionsService;
				directionsDisplay.setMap(map);

				
				directionsService.route({
				origin: {lat: position.coords.latitude, lng: position.coords.longitude},
				destination: {lat: target.data('lat'), lng: target.data('lng')},
				// Note that Javascript allows us to access the constant
				// using square brackets and a string value as its
				// "property."
				travelMode: google.maps.TravelMode['DRIVING']
				}, function(response, status) {
				if (status == 'OK') {
					directionsDisplay.setDirections(response);
				} else {
					window.alert('Directions request failed due to ' + status);
				}
				});

			});
		}*/
	  
}