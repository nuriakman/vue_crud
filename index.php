<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP - MySQL - VUE ile CRUD Örneği</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <style>
        .modal-mask {
            position: fixed;
            z-index: 9998;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, .5);
            display: table;
            transition: opacity .3s ease;
        }

        .modal-wrapper {
            display: table-cell;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="container" id="crudApp">
        <br />
        <h3 align="center">PHP - MySQL - VUE ile CRUD Örneği</h3>
        <br />
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="panel-title">Örnek Veriler</h3>
                    </div>
                    <div class="col-md-6" align="right">
                        <input type="button" class="btn btn-success btn-xs" @click="openModel" value="Kişi Ekle..." />
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>Adı</th>
                            <th>Soyadı</th>
                            <th>Düzenle</th>
                            <th>Sil</th>
                        </tr>
                        <tr v-for="row in allData">
                            <td>{{ row.first_name }}</td>
                            <td>{{ row.last_name }}</td>
                            <td><button type="button" name="edit"   class="btn btn-primary btn-xs edit"  @click="editData(row.id)">Düzenle</button></td>
                            <td><button type="button" name="delete" class="btn btn-danger btn-xs delete" @click="deleteData(row.id)">Sil</button></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div v-if="myModel">
            <transition name="model">
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" @click="myModel=false"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">{{ bolumBasligi }}</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Ad Giriniz</label>
                                        <input type="text" class="form-control" v-model="first_name" />
                                    </div>
                                    <div class="form-group">
                                        <label>Soyad Giriniz</label>
                                        <input type="text" class="form-control" v-model="last_name" />
                                    </div>
                                    <br />
                                    <div align="center">
                                        <input type="hidden" v-model="hiddenId" />
                                        <input type="button" class="btn btn-success btn-xs" v-model="actionButton" @click="submitData" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</body>

</html>

<script>

var app = new Vue({
    el: '#crudApp',
    data: {
        allData: '',
        myModel: false,
        actionButton: 'Insert',
        bolumBasligi: 'Yeni Kişi Ekle...',
    },
    methods: {
        fetchAllData: function() {
            axios.post('api.php', {
                action: 'fetchall'
            }).then(function(response) {
                app.allData = response.data;
            });
        },
        openModel: function() {
            app.first_name   = '';
            app.last_name    = '';
            app.actionButton = "Insert";
            app.bolumBasligi = "Yeni Kişi Ekle...";
            app.myModel      = true;
        },
        submitData: function() {
            if (app.first_name != '' && app.last_name != '') {
                if (app.actionButton == 'Insert') {
                    axios.post('api.php', {
                        action: 'insert',
                        firstName: app.first_name,
                        lastName: app.last_name
                    }).then(function(response) {
                        app.myModel = false;
                        app.fetchAllData();
                        app.first_name = '';
                        app.last_name  = '';
                        alert(response.data.message);
                    });
                }
                if (app.actionButton == 'Update') {
                    axios.post('api.php', {
                        action: 'update',
                        firstName: app.first_name,
                        lastName: app.last_name,
                        hiddenId: app.hiddenId
                    }).then(function(response) {
                        app.myModel = false;
                        app.fetchAllData();
                        app.first_name = '';
                        app.last_name  = '';
                        app.hiddenId   = '';
                        alert(response.data.message);
                    });
                }
            } else {
                alert("Tüm sahaları doldurunuz...");
            }
        },
        editData: function(id) {
            axios.post('api.php', {
                action: 'fetchSingle',
                id: id
            }).then(function(response) {
                app.first_name = response.data.first_name;
                app.last_name  = response.data.last_name;
                app.hiddenId   = response.data.id;
                app.myModel    = true;
                app.actionButton = 'Tamam, Kaydet';
                app.bolumBasligi = 'Kişi bilgileri güncelle...';
            });
        },
        deleteData: function(id) {
            if (confirm("Silmek istediğinize emin misiniz?")) {
                axios.post('api.php', {
                    action: 'delete',
                    id: id
                }).then(function(response) {
                    app.fetchAllData();
                    alert(response.data.message);
                });
            }
        }
    },
    created: function() {
        this.fetchAllData();
    }
});

</script>
