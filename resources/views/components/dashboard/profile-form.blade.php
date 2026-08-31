<div class="container">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <h4>User Profile</h4>
                    <hr/>
                    <div class="container-fluid m-0 p-0">
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <label>Email Address</label>
                                <input readonly id="email" placeholder="User Email" class="form-control" type="email"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>First Name</label>
                                <input id="firstName" placeholder="First Name" class="form-control" type="text"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Last Name</label>
                                <input id="lastName" placeholder="Last Name" class="form-control" type="text"/>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Mobile Number</label>
                                <input id="mobile" placeholder="Mobile" class="form-control" type="mobile"/>
                            </div>
                        </div>
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <button onclick="onUpdate()" class="btn mt-3 w-100  bg-gradient-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    getProfile();
    async function getProfile(){
        try{
            showLoader();
            let res=await axios.get("user-profile",HeaderToken());
            hideLoader();
            document.getElementById('email').value=res.data.data['email'];
            document.getElementById('firstName').value=res.data.data['firstName']
            document.getElementById('lastName').value=res.data.data['lastName']
            document.getElementById('mobile').value=res.data.data['mobile']

        }catch (e) {
           unauthorized(e.response.status)
        }
    }


    async function onUpdate(){
        let PostBody={
            "firstName":document.getElementById('firstName').value,
            "lastName":document.getElementById('lastName').value,
            "mobile":document.getElementById('mobile').value,
        }
        // console.log(PostBody);
        // showLoader();
        let res=await axios.get("/userUpdate",PostBody,HeaderToken());
        // hideLoader();
        if(res.data['status']==="success"){
            console.log('success')
        }else{
            console.log('fail');
        }
        //     successToast(res.data['message'])
        //     await getProfile();
        // }
        // else {
        //     successToast(res.data['message'])
        // }


    }


</script>
{{-- <script>
    async function getProfile();
    showLoader();
    let res=await axios.get("/user-profile")
    hideLoader();
    if(res.status===200 && res.data['status']==='success'){
        let data = res.data['data'];
        document.getElementById('email').value=data['email'];
        document.getElementById('firstName').value=data['firstName'];
        document.getElementById('lastName').value=data['lastName'];
        document.getElementById('mobile').value=data['mobile']
    }else{
        errorToast(res.data['message'])
    }
    getProfile();
</script> --}}
{{-- <script>
    async function onUpdate(){
        let PostBody = {
            "firstName":document.getElementById('firstName').value,
            "lastName":document.getElementById('lastName').value,
            "mobile":document.getElementById('mobile').value,
        }
        showLoader();
        let res = await axios.post("/userUpdate", PostBody, HeaderToken());
        hideLoader();
        if(res.data['status']==='success'){
            successToast(res.data['message'])
            await getProfile();
        }else{
            successToast(res.data['message'])
        }
    }
</script> --}}

{{-- <script>
    getProfile();

    async function getProfile() {
        try {
            showLoader();

            let res = await axios.get("/user-profile", HeaderToken());

            hideLoader();

            if (res.status === 200 && res.data['status'] === 'success') {

                let data = res.data['data'];

                document.getElementById('email').value = data['email'];
                document.getElementById('firstName').value = data['firstName'];
                document.getElementById('lastName').value = data['lastName'];
                document.getElementById('mobile').value = data['mobile'];

            } else {
                errorToast(res.data['message']);
            }

        } catch (e) {

            hideLoader();

            if (e.response) {
                unauthorized(e.response.status);
            } else {
                errorToast("Something went wrong");
            }
        }
    }
</script>


<script>
    async function onUpdate() {

        let PostBody = {
            firstName: document.getElementById('firstName').value,
            lastName: document.getElementById('lastName').value,
            mobile: document.getElementById('mobile').value
        };

        try {

            showLoader();

            let res = await axios.post(
                "/userUpdate",
                PostBody,
                HeaderToken()
            );

            hideLoader();

            if (res.data['status'] === 'success') {

                successToast(res.data['message']);

                await getProfile();

            } else {

                errorToast(res.data['message']);

            }

        } catch (e) {

            hideLoader();

            if (e.response) {
                unauthorized(e.response.status);
            } else {
                errorToast("Something went wrong");
            }
        }
    }
</script> --}}