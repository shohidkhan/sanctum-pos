<div class="container-fluid">
    <div class="row">
    <div class="col-md-12 col-sm-12 col-lg-12">
        <div class="card px-5 py-5">
            <div class="row justify-content-between ">
                <div class="align-items-center col">
                    <h4>Supplier Product</h4>
                </div>
                <div class="align-items-center col">
                    <button data-bs-toggle="modal" data-bs-target="#create-modal" class="float-end btn m-0  bg-gradient-primary">Create</button>
                </div>
            </div>
            <hr class="bg-dark "/>
            <table class="table" id="tableData">
                <thead >
                    <tr class="bg-light text-center">
                        <th class="text-center">No.</th>
                        <th class="text-center">Image</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Supplier</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Brand</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">stock</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="tableList" class="text-center">

                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    getList();
    async function getList(){
        try{
            showLoader();
            let res=await axios.get("/supplier-product-list",HeaderToken());
            hideLoader();

            let tableData=$("#tableData");
            let tableList=$("#tableList");
            tableData.DataTable().destroy();
            tableList.empty();

            res.data.forEach((item,index) => {
                let row=`
                    <tr class="text-center">
                        <td>${index+1}</td>
                        <td>
                            <img src="${item['img_url']}" width="50" height="50">
                        </td>
                        <td>
                            ${item['name']}
                        </td>
                        <td>
                            ${item['suplier']['suplier_name']}
                        </td>
                        <td>
                            ${item['category']['name']}
                        </td>
                        <td>
                            ${item['brand']['name']}
                        </td>
                        <td>
                            ${item['purchase_price']}
                        </td>
                        <td>
                            ${item['unit']}
                        </td>
                        <td>
                            ${item['stock']}
                        </td>
                        <td>
                            <button data-id="${item['id']}" class="editBtn btn btn-outline-success text-sm px-3 py-1 btn-sm m-0"><i class="fas text-sm fa-edit text-success"></i></button>
                            <button data-id="${item['id']}" class="deleteBtn btn btn-outline-danger text-sm px-3 py-1 btn-sm m-0"><i class="fa text-sm text-danger  fa-trash-alt"></i></button>
                        </td>
                    </tr>
                `;
                tableList.append(row);
            });

            $(".editBtn").on("click",async function(){
                let id=$(this).data("id");
                await fillFormData(id);
                $("#update-modal").modal("show");
            });

            $(".deleteBtn").on("click",async function(){
                let id= $(this).data("id");
                $("#deleteID").val(id);
                $("#delete-modal").modal("show");
            })

            new DataTable("#tableData",{
                order:[[0,"desc"]],
                lengthMenu:[5,10,15,20,25]
            });
        }catch(e){
            unauthorized(e.response.status);
        }
    }
</script>