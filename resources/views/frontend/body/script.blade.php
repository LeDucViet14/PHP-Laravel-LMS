{{-- /// Start Wishlist Add Option // --}}
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    // Add wishlist
    function addToWishList(course_id) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/add-to-wishlist/" + course_id,

            success: function(data) {
                // Start Message 
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000
                })
                if ($.isEmptyObject(data.error)) {

                    Toast.fire({
                        type: 'success',
                        icon: 'success',
                        title: data.success,
                    })
                } else {

                    Toast.fire({
                        type: 'error',
                        icon: 'error',
                        title: data.error,
                    })
                }
                // End Message   
            }
        });


    }
</script>
{{-- /// End Add Wishlist  // --}}



{{-- /// Start render Wishlist // --}}
<script type="text/javascript">
    function wishlist() {
        $.ajax({
            // gửi request đến route /get-wishlist-course/
            type: "GET",
            dataType: 'json',
            url: "/get-wishlist-course/",
            // khi thành công dữ liệu trả về sẽ xử lí trong hàm success
            success: function(response) {
                var rows = "";
                $('#wishQty').text(response.wishQty);
                // lặp vòng for
                $.each(response.wishlist, function(key, value) {
                    rows += `
                        <div class="col-lg-4 responsive-column-half">
                            <div class="card card-item">
                                <div class="card-image">
                                    <a href="/course/details/${value.course.id}/${value.course.course_name_slug}" class="d-block">
                                        <img class="card-img-top" src="/${value.course.course_image}" alt="Card image cap">
                                    </a>
                                
                                </div>
                                <div class="card-body">
                                    <h6 class="ribbon ribbon-blue-bg fs-14 mb-3">${value.course.label}</h6>
                                    <h5 class="card-title"><a href="/course/details/${value.course.id}/${value.course.course_name_slug}">${value.course.course_name}</a></h5> 
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        ${value.course.discount_price == null 
                                        ?`<p class="card-price text-black font-weight-bold">$${value.course.selling_price}</p>`
                                        :`<p class="card-price text-black font-weight-bold">$${value.course.discount_price} <span class="before-price font-weight-medium">$${value.course.selling_price}</span></p>`
                                        } 
                                    
                                        <div id="${value.id}" onclick="wishlistRemove(this.id)"  class="icon-element icon-element-sm shadow-sm cursor-pointer" data-toggle="tooltip" data-placement="top" title="Remove from Wishlist"><i class="la la-heart"></i></div>
                                    </div>
                                </div> 
                            </div> 
                        </div> 
             `
                });
                $('#wishlist').html(rows);
            }
        })
    }
    wishlist();

    /// start Remove WishList    // 
    function wishlistRemove(id) {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '/wishlist-remove/' + id,

            success: function(data) {
                wishlist();
                // Start Message 
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 6000
                })
                if ($.isEmptyObject(data.error)) {

                    Toast.fire({
                        type: 'success',
                        icon: 'success',
                        title: data.success,
                    })
                } else {

                    Toast.fire({
                        type: 'error',
                        icon: 'error',
                        title: data.error,
                    })
                }
                // End Message   
            }
        })
    }
</script>