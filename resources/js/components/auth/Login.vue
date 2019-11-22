<template>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-form-title" style="background-image: url(images/bg-01.jpg);">
					<span class="login100-form-title-1">
						Sign In
					</span>
                </div>

                <form class="login100-form validate-form" @submit.prevent="login">
                    <div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
                        <span class="label-input100">Username</span>
                        <input class="input100" type="email" name="username" placeholder="Enter username" v-model="form.email" required>
                        <span class="focus-input100"></span>
                    </div>

                    <div class="wrap-input100 validate-input m-b-18" data-validate = "Password is required">
                        <span class="label-input100">Password</span>
                        <input class="input100" type="password" name="pass" placeholder="Enter password" v-model="form.password" required>
                        <span class="focus-input100"></span>
                    </div>

<!--                    <div class="flex-sb-m w-full p-b-30">-->
<!--                        <div class="contact100-form-checkbox">-->
<!--                            <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">-->
<!--                            <label class="label-checkbox100" for="ckb1">-->
<!--                                Remember me-->
<!--                            </label>-->
<!--                        </div>-->

<!--                        <div>-->
<!--                            <a href="#" class="txt1">-->
<!--                                Forgot Password?-->
<!--                            </a>-->
<!--                        </div>-->
<!--                    </div>-->

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
      data(){
          return {
              form:{
                  email:'',
                  password:''
              }
          }
      },
        created(){
            this.listen();

            if (User.loggedIn()){
                console.log(User.id())
                this.$router.push('/machines');
            }
        },
        methods:{
            login(){
                User.login(this.form);
            },
            goRegister(){
                console.log('here now')
                //this.$router.push('/signup')
            },
            listen(){
                eventBus.$on('invalidCredentials',()=>{
                    this.$toastr.w('Sorry,You entered invalid credentials.');
                });
                eventBus.$on('invalidTokens',()=>{
                    this.$toastr.w('Sorry,Your token is valid.')
                });
            }
        }
    }
</script>

<style scoped>
    /*//////////////////////////////////////////////////////////////////
[ Utility ]*/
    .txt1 {
        font-family: Poppins-Regular;
        font-size: 13px;
        line-height: 1.4;
        color: #999999;
    }
      .limiter {
        width: 100%;
        margin: 0 auto;
    }

    .container-login100 {
        width: 100%;
        min-height: 100vh;
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        padding: 15px;
        background: #ebeeef;
    }


    .wrap-login100 {
        width: 670px;
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }

    /*==================================================================
    [ Title form ]*/
    .login100-form-title {
        width: 100%;
        position: relative;
        z-index: 1;
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        flex-wrap: wrap;
        flex-direction: column;
        align-items: center;

        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;

        padding: 70px 15px 74px 15px;
    }

    .login100-form-title-1 {
        font-family: Poppins-Bold;
        font-size: 30px;
        color: #fff;
        text-transform: uppercase;
        line-height: 1.2;
        text-align: center;
    }

    .login100-form-title::before {
        content: "";
        display: block;
        position: absolute;
        z-index: -1;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: rgba(54,84,99,0.7);
    }

    /*==================================================================
  [ Form ]*/
    .login100-form {
        width: 100%;
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        padding: 43px 88px 93px 190px;
    }


    /*------------------------------------------------------------------
    [ Input ]*/

    .wrap-input100 {
        width: 100%;
        position: relative;
        border-bottom: 1px solid #b2b2b2;
    }

    .label-input100 {
        font-family: Poppins-Regular;
        font-size: 15px;
        color: #808080;
        line-height: 1.2;
        text-align: right;

        position: absolute;
        top: 14px;
        left: -105px;
        width: 80px;

    }

    /*---------------------------------------------*/
    .input100 {
        font-family: Poppins-Regular;
        font-size: 15px;
        color: #555555;
        line-height: 1.2;
        display: block;
        width: 100%;
        background: transparent;
        padding: 0 5px;
        border: none;
    }


    .focus-input100 {
        position: absolute;
        display: block;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
    }

    .focus-input100::before {
        content: "";
        display: block;
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 0;
        height: 1px;

        -webkit-transition: all 0.6s;
        -o-transition: all 0.6s;
        -moz-transition: all 0.6s;
        transition: all 0.6s;

        background: #57b846;
    }


    /*---------------------------------------------*/
    input.input100 {
        height: 45px;
        margin-bottom: 10px;
    }


    .input100:focus + .focus-input100::before {
        width: 100%;
    }

    .has-val.input100 + .focus-input100::before {
        width: 100%;
    }

    /*==================================================================
    [ Restyle Checkbox ]*/

    .input-checkbox100 {
        display: none;
    }

    .label-checkbox100 {
        font-family: Poppins-Regular;
        font-size: 13px;
        color: #999999;
        line-height: 1.4;

        display: block;
        position: relative;
        padding-left: 26px;
        cursor: pointer;
    }

    .label-checkbox100::before {
        content: "\f00c";
        font-family: FontAwesome;
        font-size: 13px;
        color: transparent;

        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        width: 18px;
        height: 18px;
        border-radius: 2px;
        background: #fff;
        border: 1px solid #e6e6e6;
        left: 0;
        top: 50%;
        -webkit-transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -o-transform: translateY(-50%);
        transform: translateY(-50%);
    }

    .input-checkbox100:checked + .label-checkbox100::before {
        color: #57b846;
    }

    /*------------------------------------------------------------------
    [ Button ]*/
    .container-login100-form-btn {
        width: 100%;
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        flex-wrap: wrap;
        margin-top: 20px;

    }

    .login100-form-btn {
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0 20px;
        min-width: 160px;
        height: 50px;
        background-color: #57b846;
        border-radius: 25px;

        font-family: Poppins-Regular;
        font-size: 16px;
        color: #fff;
        line-height: 1.2;

        -webkit-transition: all 0.4s;
        -o-transition: all 0.4s;
        -moz-transition: all 0.4s;
        transition: all 0.4s;
        border: none;
    }

    .login100-form-btn:hover {
        background-color: #333333;
        border: none;
    }


    /*------------------------------------------------------------------
    [ Responsive ]*/

    @media (max-width: 576px) {
        .login100-form {
            padding: 43px 15px 57px 117px;
        }
    }

    @media (max-width: 480px) {
        .login100-form {
            padding: 43px 15px 57px 15px;
        }

        .label-input100 {
            text-align: left;
            position: unset;
            top: unset;
            left: unset;
            width: 100%;
            padding: 0 5px;
        }
    }


</style>

