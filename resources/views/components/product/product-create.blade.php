<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Product</h5>
                </div>
                <div class="modal-body">
                    <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Category</label>

                                <select type="text" class="form-control form-select" id="productCategory">
                                    <option value="">Select Category</option>
                                </select>

                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="productName">

                                <label class="form-label mt-2">Price</label>
                                <input type="text" class="form-control" id="productPrice">

                                <label class="form-label mt-2">Unit</label>
                                <input type="text" class="form-control" id="productUnit">

                                <label class="form-label mt-2">Image</label>
                                <input type="file" class="form-control" id="productImg">

                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary mx-2" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="Save()" id="save-btn" class="btn bg-gradient-success" >Save</button>
                </div>
            </div>
    </div>
</div>



<script>
    FillCategoryDropDown();
    async function FillCategoryDropDown(){
        let res = await axios.get("/list-category",HeaderToken())
        res.data.forEach(function(item, i){
            let option = `<option value = "${item['id']}">${item['name']}</option>`
            $("#productCategory").append(option);
        })
    }

    
async function Save(){

    let productCategory=document.getElementById('productCategory').value;
    let productName = document.getElementById('productName').value;
    let productPrice = document.getElementById('productPrice').value;
    let productUnit = document.getElementById('productUnit').value;
    let productImg = document.getElementById('productImg').files[0];

    // console.log(productImg);
    // Frontend Validation
    if(productCategory.trim() === ""){
        errorToast("Please select a category!");
        return;
    }

    if(productName.trim() === ""){
        errorToast("Please enter product name!");
        return;
    }

    if(productPrice.trim() === ""){
        errorToast("Please enter product price!");
        return;
    }

    if(productUnit.trim() === ""){
        errorToast("Please enter product unit!");
        return;
    }
    if(!productImg){
        errorToast("Please select a product image!");
        return;
    }

    let allwoedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    if(!allwoedTypes.includes(productImg.type)){
        errorToast("Please select a JPG, JPEG or PNG image!")
        return;
    }

    let PostBody = new FormData();

    PostBody.append("name", productName);
    PostBody.append("price", productPrice);
    PostBody.append("unit", productUnit);
    PostBody.append("category_id", productCategory);
    PostBody.append("img_url", productImg);



    // function config(){
    //     return {
    //         headers:{
    //             'Content-Type':'multipart/form-data'
    //         }
    //     }
    // }
    // console.log(PostBody)

    showLoader();
    try {
    let res = await axios.post("/create-product", PostBody, HeaderToken());

    if(res.data['status']==="Success"){
        successToast(res.data['message'])
        document.getElementById("save-form").reset();
        document.getElementById('modal-close').click();
        await getList();
    }
    else{

        errorToast(res.data['message'])

    }
    } catch(error) {
        if(error.response){
            errorToast(
                error.response.data.message || "Something went wrong!"
            );
        }else{
            errorToast("Network error!");
        }
    }finally {
        hideLoader();
    }
}
</script>


{{-- <script>



    FillCategoryDropDown();
    async function FillCategoryDropDown(){
        let res = await axios.get("/list-category",HeaderToken())
        res.data['rows'].forEach(function (item,i) {
            let option=`<option value="${item['id']}">${item['name']}</option>`
            $("#productCategory").append(option);
        })
    }

    async function Save() {
        try {
            let productCategory=document.getElementById('productCategory').value;
            let productName = document.getElementById('productName').value;
            let productPrice = document.getElementById('productPrice').value;
            let productUnit = document.getElementById('productUnit').value;
            document.getElementById('modal-close').click();
            let PostBody= {
                "name":productName,
                "price":productPrice,
                "unit":productUnit,
                "category_id":productCategory
            }

            showLoader();
            let res = await axios.post("/create-product",PostBody,HeaderToken())
            document.getElementById("save-form").reset();
            hideLoader();

            if(res.data['status']==="success"){
                successToast(res.data['message'])
                await getList();
            }
            else{
                errorToast(res.data['message'])
            }

        }catch (e) {
            unauthorized(e.response.status)
        }
    }
</script> --}}
