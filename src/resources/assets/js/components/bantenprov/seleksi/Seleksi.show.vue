<template>
  <div class="card">
    <div class="card-header">
      <i class="fa fa-table" aria-hidden="true"></i> Show seleksi 

      <ul class="nav nav-pills card-header-pills pull-right">
        <li class="nav-item">
          <button class="btn btn-primary btn-sm" role="button" @click="back">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
          </button>
        </li>
      </ul>
    </div>

    <div class="card-body">
      <dl class="row">
          <dt class="col-4">Pendaftaran :</dt>
          <dd class="col-5">{{ model.pendaftaran.tanggal_pendaftaran }}</dd>

          <dt class="col-4">Nama Siswa :</dt>
          <dd class="col-5">{{ model.siswa.nama_siswa }}</dd>

          <dt class="col-4">Nilai :</dt>
          <dd class="col-5">{{ model.nilai }}</dd>
      </dl>
    </div>

     <div class="card-footer text-muted">
        <div class="row">
          <div class="col-md">
            <b>Username :</b> {{ model.user.name }}
          </div>
          <div class="col-md">
            <div class="col-md text-right">Dibuat : {{ model.created_at }}</div>
            <div class="col-md text-right">Diperbaiki : {{ model.updated_at }}</div>
          </div>
        </div>
      </div>
  </div>
</template>

<script>
export default {
  mounted() {
    axios.get('api/seleksi/' + this.$route.params.id)
      .then(response => {
        if (response.data.status == true) {
          this.model.user = response.data.user,
          this.model.siswa = response.data.siswa;
          this.model.nilai = response.data.seleksi.nilai_id;
          this.model.pendaftaran = response.data.pendaftaran;
          this.model.created_at = response.data.seleksi.created_at;
          this.model.updated_at = response.data.seleksi.updated_at;
        } else {
          alert('Failed');
        }
      })
      .catch(function(response) {
        alert('Break');
        window.location.href = '#/admin/seleksi';
      })
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
    back() {
      window.location = '#/admin/seleksi';
    }
  }
}
</script>
