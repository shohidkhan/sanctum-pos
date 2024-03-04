<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Product</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Brand</label>
                                <select type="text" class="form-control form-select" id="brand">
                                    <option value="">Select Brand</option>
                                </select>

                                <label class="form-label">Supplier</label>
                                <select type="text" class="form-control form-select" id="suplier">
                                    <option value="">Select Supplier</option>
                                </select>

                                <label class="form-label">Category</label>
                                <select type="text" class="form-control form-select" id="category">
                                    <option value="">Select Category</option>
                                </select>

                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="supplier_product_name">

                                <label class="form-label mt-2">Purchase Price</label>
                                <input type="text" class="form-control" id="product_purchase_price">

                                <label class="form-label mt-2">Stock</label>
                                <input type="text" class="form-control" id="supplier_product_stock">

                                <label class="form-label mt-2">Unit</label>
                                <input type="text" class="form-control" id="supplier_product_unit">

                                <br/>
                                <img class="w-15" id="oldImg" src="{{asset('images/default.jpg')}}"/>
                                <br/>
                                <label class="form-label mt-2">Image</label>
                                <input oninput="oldImg.src=window.URL.createObjectURL(this.files[0])"  type="file" class="form-control" id="supplier_product_img_url">

                                <input type="text" class="d-nne" id="updateID">
                                {{-- <input type="text" readonly class="d-nne"  id="oldImgFromDb"> --}}
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button onclick="update()" id="update-btn" class="btn bg-gradient-success" >Update</button>
            </div>

        </div>
    </div>
</div>

<script>
    getAndFillCategory();
    getAndFillBrand();
    getAndFillSupplier();

    async function getAndFillSupplier() {
        let res= await axios.get("/suplier-list",HeaderToken());

        res.data.forEach((item)=>{
            let option=`<option value="${item["id"]}">${item["suplier_name"]}</option>`;

            $("#suplier").append(option);
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
            $("#brand").append(option);
        })
    }
    async function getAndFillCategory(){
      let res=await axios.get("/category-list",HeaderToken());
      

      res.data.forEach(function(item){
          let option =`
              <option  value="${item["id"]}">${item["name"]}</option>`;
              $("#category").append(option);
      });
    }
      
    async function fillFormData(id){
        document.getElementById("updateID").value=id;

        let res=await axios.post("/single-supplier-product",{id:id},HeaderToken());
        console.log(res.data)

        document.getElementById("supplier_product_name").value=res.data["name"];
        document.getElementById("category").value=res.data["category_id"];
        document.getElementById("brand").value=res.data["brand_id"];
        document.getElementById("suplier").value=res.data["suplier_id"];
        document.getElementById("product_purchase_price").value=res.data["purchase_price"];
        document.getElementById("supplier_product_stock").value=res.data["stock"];
        document.getElementById("supplier_product_unit").value=res.data["unit"];
        document.getElementById("oldImg").src=res.data["img_url"];
    }

    async function update(){
        try{

            let id=document.getElementById("updateID").value;
            let name=document.getElementById("supplier_product_name").value;
            let category_id=document.getElementById("category").value;
            let brand_id=document.getElementById("brand").value;
            let suplier_id=document.getElementById("suplier").value;
            let purchase_price=document.getElementById("product_purchase_price").value;
            let stock=document.getElementById("supplier_product_stock").value;
            let unit=document.getElementById("supplier_product_unit").value;
            let img_url=document.getElementById("supplier_product_img_url").files[0];


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
            }else{
                document.getElementById("update-modal-close").click();
                let formData = new FormData();

                formData.append("name",name);
                formData.append("category_id",category_id);
                formData.append("brand_id",brand_id);
                formData.append("suplier_id",suplier_id);
                formData.append("purchase_price",purchase_price);
                formData.append("stock",stock);
                formData.append("unit",unit);
                formData.append("img_url",img_url);
                formData.append("id",id);

                const config={
                    headers:{
                        "content-type" : "multipart/form-data",
                        "Authorization":getToken()
                    }
                }

                showLoader();
                let res=await axios.post("/update-supplier-product",formData,HeaderToken());
                hideLoader();

                if(res.status===200 && res.data["status"]==='success'){
                    document.getElementById("update-form").reset();
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