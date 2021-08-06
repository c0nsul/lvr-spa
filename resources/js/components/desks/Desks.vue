<template>
    <div class="container">
        <h1>Desks</h1>
        <div class="row">

            <div class="col-lg-4" v-for="desk in desks">
                <div class="card mt-3" >
                    <a href="#" class="card-body">
                        <h5 class="card-title">{{desk.name}}</h5>
                    </a>
                </div>
            </div>
        </div>

        <div class="alert alert-danger" role="alert" v-if="error">
            Erorr data loading!
        </div>

        <button class="btn btn-primary" type="button" disabled v-if="loading">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        </button>



    </div>
</template>

<script>
export default {
    data(){
        return {
            desks: [],
            error: false,
            loading: true
        }
    },
    mounted() {
        axios.get('/api/V1/desks')
        .then(response => {
            this.desks = response.data.data;
        })
        .finally(()=>{
            this.loading = false;
        })
        .catch(error =>{
            console.log(error);
            this.error = true;
        })
    }
}
</script>
