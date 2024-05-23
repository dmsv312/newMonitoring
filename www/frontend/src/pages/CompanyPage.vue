<template>
  <div>
    <my-button @click="showDialog">Create Company</my-button>

    <my-dialog v-model:show="dialogVisible">
      <company-form @create="createCompany">
      </company-form>
    </my-dialog>

    <companies-list @remove="removeCompany" :companies="companies">
    </companies-list>
  </div>
</template>

<script>
import axios from 'axios'
import CompaniesList from "@/components/CompaniesList";
import CompanyForm from "@/components/CompanyForm";

export default {
  data() {
    return {
      companies: [],
      dialogVisible: false
    };
  },
  name: 'company-page',
  components: {
    CompaniesList,
    CompanyForm
  },
  mounted() {
    this.fetchCompanies();
  },
  methods: {
    showDialog() {
      this.dialogVisible = true;
    },
    fetchCompanies() {
      axios.get('/api/v1/companies')
        .then(response => {
        console.log(response);
        this.companies = response.data.data;
      }).catch(error => {
        console.log(error)
      });
    },
    createCompany(company) {
      axios.post('/api/v1/companies', {
        name: company.name,
        email: company.email
      }).then(response => {
        this.fetchCompanies();
        this.dialogVisible = false;
        console.log(response);
      }).catch(error => {
        console.log(error)
      });
    },
    removeCompany(company) {
      axios.delete('/api/v1/companies/' + company.id).then(response => {
        this.fetchCompanies();
        console.log(response);
      }).catch(error => {
        console.log(error)
      });
    }
  }
}
</script>

<style>

</style>
