<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Supplier Product</h5>
                </div>
                <div class="modal-body">
                    <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">

                                <label class="form-label">Brand</label>
                                <select type="text" class="form-control form-select" id="brand_id">
                                    <option value="">Select Brand</option>
                                </select>

                                <label class="form-label">Supplier</label>
                                <select type="text" class="form-control form-select" id="suplier_id">
                                    <option value="">Select Supplier</option>
                                </select>

                                <label class="form-label">Category</label>
                                <select type="text" class="form-control form-select" id="category_id">
                                    <option value="">Select Category</option>
                                </select>

                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="name">

                                <label class="form-label mt-2">Purchase Price</label>
                                <input type="text" class="form-control" id="purchase_price">

                                <label class="form-label mt-2">Stock</label>
                                <input type="text" class="form-control" id="stock">

                                <label class="form-label mt-2">Unit</label>
                                <input type="text" class="form-control" id="unit">

                                <br/>
                                <img class="w-15" id="newImg" src="{{asset('images/default.jpg')}}"/>
                                <br/>
                                <label class="form-label">Image</label>
                                <input oninput="newImg.src=window.URL.createObjectURL(this.files[0])" type="file" class="form-control" id="img_url">
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
    getAndFillCategory();
    getAndFillBrand();
    getAndFillSupplier();
    async function getAndFillCategory(){
      let res=await axios.get("/category-list",HeaderToken());

      res.data.forEach((item)=>{
          let option=`
              <option  value="${item["id"]}">${item["name"]}</option>`;
              $("#category_id").append(option);
      });
    }
      
    async function getAndFillBrand(){
        let res= await axios.get("/brand-list",HeaderToken());
        res.data.forEach((item)=>{
            let option=`
            <option value="${item["id"]}">
                ${[item["name"]]}
            </option>
            `;
            $("#brand_id").append(option);
        })
    }

    async function getAndFillSupplier() {
        let res= await axios.get("/suplier-list",HeaderToken());


        res.data.forEach((item)=>{
            let option=`<option value="${item["id"]}">${item["suplier_name"]}</option>`;

            $("#suplier_id").append(option);
        });
    }

    async function Save(){
        try{
            let name = document.getElementById('name').value;
            let purchase_price = document.getElementById('purchase_price').value;
            let unit = document.getElementById('unit').value;
            let category_id = document.getElementById('category_id').value;
            let brand_id = document.getElementById('brand_id').value;
            let suplier_id = document.getElementById('suplier_id').value;
            let stock = document.getElementById('stock').value;
            let img_url = document.getElementById('img_url').files[0];

            // console.log(productName,productPrice,productUnit,productCategory,productImg,productBrand,productSupplier,productStock);
            
            if(name.length===0){
                errorToast("Product Name Required !");
            }else if(brand_id.length===0){
                errorToast("Product Brand Required !");
            }else if(category_id.length===0){
                errorToast("Product Category Required !");
            }else if(purchase_price.length===0){
                errorToast("Product Price Required !");
            }else if(suplier_id.length===0){
                errorToast("Product Supplier Required !");
            }else if(unit.length===0){
                errorToast("Product Unit Required !");
            }else if(stock.length===0){
                errorToast("Product Stock Required !");
            }else if(!img_url){
                errorToast("Product Image Required !");
            }else{
                document.getElementById("modal-close").click();

                let formData= new FormData();

                formData.append("name",name);
                formData.append("purchase_price",purchase_price);
                formData.append("unit",unit);
                formData.append("category_id",category_id);
                formData.append("brand_id",brand_id);
                formData.append("suplier_id",suplier_id);
                formData.append("stock",stock);
                formData.append("img_url",img_url);

                const config={
                    headers:{
                        "content-type":"multipart/form-data",
                        "Authorization":getToken()
                    }
                }

                showLoader();
                let res= await axios.post("/create-supplier-product",formData,config);
                hideLoader();

                if(res.status===200 && res.data["status"]==='success'){
                    document.getElementById("save-form").reset();
                    await getList();
                    successToast(res.data["message"]);
                }else{
                    errorToast(res.data["message"]);
                }
            }
        }catch(e){
           errorToast(e.message)
       }
    }
</script>