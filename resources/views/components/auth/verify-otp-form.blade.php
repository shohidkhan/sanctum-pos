<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90  p-4">
                <div class="card-body">
                    <h4>ENTER OTP CODE</h4>
                    <br/>
                    <label>4 Digit Code Here</label>
                    <input id="otp" placeholder="Code" class="form-control" type="text"/>
                    <br/>
                    <button onclick="VerifyOtp()"  class="btn w-100 float-end bg-gradient-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>


   async function VerifyOtp() {
        let email=sessionStorage.getItem("email");
        let otp=document.getElementById("otp").value;
        let postBody={"email":email, "otp":otp}
        if(otp.length===0){
            errorToast("Please enter otp code");
        }else if(otp.length < 4){
            errorToast("Please enter valid otp code");
        }
        else{
            showLoader();
            let res = await axios.post("/verifyOtp",postBody);
            hideLoader();

            if(res.status===200 && res.data['status']==='success'){
                successToast(res.data['message']);
                setToken(res.data['token'])
                setTimeout(() => {
                    window.location.href="/reset-password";
                }, 1000);
            }else{
                errorToast(res.data['message']);
            }
        }
    }





</script>
