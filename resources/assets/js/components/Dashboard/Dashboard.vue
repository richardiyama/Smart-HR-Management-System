<template>
    <div>
          <div class="row">

                    <div class="col-md-6 col-sm-6 col-xs-12" v-if="role_id==1 || role_id==6">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Payroll in 6 Month</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><button @click="gopayroll" type="button" class="btn btn-success">Go To Payroll</button></li>
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>                 
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                               <div>
                                <apexchart width="500" type="bar" :options="payroll_options" :series="payroll_series"></apexchart>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-12" v-if="role_id==3 || role_id==5 || role_id==1">
                        <div class="x_panel tile fixed_height_320 overflow_hidden">
                            <div class="x_title">
                                <h2>Number of Employees per Company</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                        <li><button @click="view_employee"  class="btn btn-success">View Employees</button></li>
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                               <apexchart width="380" type="pie" :options="options" :series="options.series"></apexchart>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
</template>

<script>
export default {
name: "Dashboard",
props: ["the_series", "the_labels", "months", "payroll_data", "pay_series", "role_id"],
data() {
    return {
        options: { 
        series:[],
        labels:[],
        },
        payroll_options: {
            chart: {
                id: "payroll_graph",
            },
            xaxis: {
                categories:[]
            }
        },
        payroll_series: []
    }
},
methods: {
    view_employee() {
    window.location.replace(this.APP_URL+'/employees');
    },

    gopayroll() {
     window.location.replace(this.APP_URL+'/payroll/report');
    }
},
created() {
    this.options.labels = JSON.parse(this.the_labels);
    this.options.series = JSON.parse(this.the_series);
    this.payroll_options.xaxis.categories = JSON.parse(this.months);
    this.payroll_series = JSON.parse(this.pay_series);

   // console.log(this.payroll_series);
    
}
}
</script>

<style>

</style>
