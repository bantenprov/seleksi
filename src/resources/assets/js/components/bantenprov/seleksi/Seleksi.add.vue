<template>
  <div class="card">
    <div class="card-header">
      <i class="fa fa-table" aria-hidden="true"></i> Add seleksi

      <ul class="nav nav-pills card-header-pills pull-right">
        <li class="nav-item">
          <button class="btn btn-primary btn-sm" role="button" @click="back">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
          </button>
        </li>
      </ul>
    </div>

    <div class="card-body">
      <vue-form class="form-horizontal form-validation" :state="state" @submit.prevent="onSubmit">

        <div class="form-row mt-4">
					<div class="col-md">
						<validate tag="div">
						<label for="pendaftaran_id">Pendaftaran</label>
						<v-select name="pendaftaran_id" v-model="model.pendaftaran" :options="pendaftaran" class="mb-4"></v-select>

						<field-messages name="pendaftaran_id" show="$invalid && $submitted" class="text-danger">
							<small class="form-text text-success">Looks good!</small>
							<small class="form-text text-danger" slot="required">Pendaftaran is a required field</small>
						</field-messages>
						</validate>
					</div>
				</div>

        <!-- <div class="form-row mt-4">
          <div class="col-md">
            <validate tag="div">
            <label for="nomor_un">Siswa</label>
            <v-select name="nomor_un" v-model="model.siswa" :options="siswa" class="mb-4"></v-select>

            <field-messages name="nomor_un" show="$invalid && $submitted" class="text-danger">
              <small class="form-text text-success">Looks good!</small>
              <small class="form-text text-danger" slot="required">Siswa is a required field</small>
            </field-messages>
            </validate>
          </div>
        </div> -->

        <div class="form-row mt-4">
          <div class="col-md">
            <validate tag="div">
            <label for="nilai_id">Nama Siswa</label>
            <v-select name="nilai_id" v-model="model.nilai" :options="nilai" class="mb-4"></v-select>

            <field-messages name="nilai_id" show="$invalid && $submitted" class="text-danger">
              <small class="form-text text-success">Looks good!</small>
              <small class="form-text text-danger" slot="required">Nama Siswa is a required field</small>
            </field-messages>
            </validate>
          </div>
        </div>

        <div class="form-row mt-4">
					<div class="col-md">
						<validate tag="div">
						<label for="user_id">Username</label>
						<v-select name="user_id" v-model="model.user" :options="user" class="mb-4"></v-select>

						<field-messages name="user_id" show="$invalid && $submitted" class="text-danger">
							<small class="form-text text-success">Looks good!</small>
							<small class="form-text text-danger" slot="required">Username is a required field</small>
						</field-messages>
						</validate>
					</div>
				</div>

        <div class="form-row mt-4">
          <div class="col-md">
            <button type="submit" class="btn btn-primary">Submit</button>

            <button type="reset" class="btn btn-secondary" @click="reset">Reset</button>
          </div>
        </div>

      </vue-form>
    </div>
  </div>
</template>

<script>
export default {
  mounted(){
    axios.get('api/seleksi/create')
    .then(response => {
      if (response.data.status == true) {
        this.model.user = response.data.current_user;

        response.data.pendaftaran.forEach(element => {
          this.pendaftaran.push(element);
        });
        response.data.siswa.forEach(element => {
          this.siswa.push(element);
        });
        response.data.nilai.forEach(element => {
          this.nilai.push(element);
        });
        if(response.data.user_special == true){
          response.data.user.forEach(user_element => {
            this.user.push(user_element);
          });
        }else{
          this.user.push(response.data.user);
        }
      } else {
        alert('Failed');
      }
    })
    .catch(function(response) {
      alert('Break');
      window.location.href = '#/admin/seleksi';
    });
  },
  data() {
    return {
      state: {},
      model: {
        nilai: "",
        siswa: "",
        user: "",
        pendaftaran: "",
      },
      pendaftaran: [],
      user: [],
      siswa: [],
      nilai: [],
    }
  },
  methods: {
    onSubmit: function() {
      let app = this;

      if (this.state.$invalid) {
        return;
      } else {
        axios.post('api/seleksi', {
            nilai_id: this.model.nilai.total,
            pendaftaran_id: this.model.pendaftaran.id,
            nomor_un: this.model.nilai.nomor_un,
            siswa_id: this.model.siswa.id,
            user_id: this.model.user.id
          })
          .then(response => {
            if (response.data.status == true) {
              if(response.data.message == 'success'){
                alert(response.data.message);
                app.back();
              }else{
                alert(response.data.message);
              }
            } else {
              alert(response.data.message);
            }
          })
          .catch(function(response) {
            alert('Break');
          });
      }
    },
    reset() {
      this.model = {
          tanggal_seleksi: ""
      };
    },
    back() {
      window.location = '#/admin/seleksi';
    }
  }
}
</script>