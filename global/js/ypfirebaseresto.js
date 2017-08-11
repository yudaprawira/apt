//CLASS REQUIRED ypFireBase
function ypFireBaseResto(resto, path) 
{
    var table = 'resto/'+resto+'/'+path;

    this.resto = resto;
    this.yp = new ypFireBase(table);
}
ypFireBaseResto.prototype = {
    dataMeja: function(data)
    {
        this.yp.insert(data);
    },
    getMeja: function(id)
    {
        return this.yp.get(id);
    },
    update: function(id, data)
    {
        return this.yp.update(id, data);
    },
    getMejaMember: function(member_id)
    {
        return this.yp.get().orderByChild("member_id").equalTo(member_id);
    },
    pelayan: function(data)
    {
        this.yp.insert(data);
    },
    getPelayan: function()
    {
        return this.yp.get();
    },
    statusPelayan:function()
    {
        var resto   = this.resto;
        var rowUser = this.getPelayan();

        rowUser.on('value', function (snapshot) {
            if ( snapshot.numChildren()>0 )
            {
                var list = '';
                var isOnline = false;
                snapshot.forEach(function(child){
                    if ( child.val().online )
                    {
                        isOnline = true;
                        list+= '<li>'+ child.val().nama +'</li>';
                    }
                });

                if ( isOnline )
                {
                    $('body').attr('pelayan-'+resto, true);
                    $('.icon-pelayan').attr('src', $('.icon-pelayan').attr('src-online'));
                }
                else
                {
                    $('body').removeAttr('pelayan-'+resto);
                    $('.icon-pelayan').attr('src', $('.icon-pelayan').attr('src-offline' ));
                }

                $('#list-pelayan').html('<ul class="features_list_detailed">'+list+'</ul>');
            }
            else
            {
                $('body').removeAttr('pelayan-'+resto);
                $('.icon-pelayan').attr('src', $('.icon-pelayan').attr('src-offline'));
            }
        });
    },
    viewMeja: function(member_id)
    {
        myApp.popup('.popup-meja');

        var row = this.yp.get();
        var resto = this.resto;

        row.on('value', function (snapshot) {
            var html = '';
            if (snapshot.hasChildren()) 
            {
                snapshot.forEach(function (child) {
                    var id = child.getKey();
                    var obj = child.val();
                    html += '<span class="'+(obj.status=='dipakai' ? (obj.member_id==member_id ? 'meja-saya' : 'meja-terpakai') : '')+'">'
                            +'<input id="meja-'+id+'" data-resto="'+  resto +'" name="meja" class="pilih-meja" value="'+id+'" '+(obj.status=='dipakai' && obj.member_id!=member_id ? 'disabled=1' : '')+' type="radio">'
                            +'<label for="meja-'+id+'" style="margin: 5px; padding: 30px 15px;">Meja '+id+'</label>'
                        +'</span>';

                });
            }
            $('#statusMeja').html(html);
            
        });
    },
    setMeja: function(mejaID, memberID){
        
        var currentDateTime = this.getDateTime();

        //update User
        ypMember = new ypFireBase("member");
        ypMember.update(memberID, {
            'meja_id' : mejaID,
            'meja_pakai' : currentDateTime
        });

        //update another 
        var rows = this.getMejaMember(memberID);
        rows.once('value', function (snapshot) {
            snapshot.forEach(function (child) {
                child.ref.update({"member_id":"-", "status":"tersedia"});
            });
        });

        //update table
        this.yp.update(mejaID, {
            'member_id' : memberID,
            'status' : 'dipakai',
            'waktu' : currentDateTime
        });
    },
    keranjang: function(member_id, data)
    {
        var rows = this.yp.get(member_id);
            rows.once('value', function(snapshot){
                if (snapshot.hasChildren()) 
                {
                   snapshot.forEach(function (child) {
                        var id = child.getKey();
                        var dt = data[id];
                            dt.jumlah=parseInt(dt.jumlah)+parseInt(child.val().jumlah);
                        child.ref.update(dt);
                    });
                } 
                else 
                {
                    snapshot.ref.set(data);
                }
            });
    },
    getDateTime: function ()
    {
        var date = new Date();

        return date.getFullYear() + '-' +
            ('00' + (date.getMonth()+1)).slice(-2) + '-' +
            ('00' + date.getDate()).slice(-2) + ' ' + 
            ('00' + date.getHours()).slice(-2) + ':' + 
            ('00' + date.getMinutes()).slice(-2) + ':' + 
            ('00' + date.getSeconds()).slice(-2);
    }
}