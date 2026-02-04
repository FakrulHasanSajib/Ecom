@extends('backEnd.layouts.master')
@section('title' 'Order all')

@section('css')
<link rel="stylesheet" href="{{asset('public/backEnd/')}}/assets/css/allorder.css">

@endsection
@section('content')

<div class="container" style="max-width: 90%">
      <div class="row mt-3">
        <div class="col-12 col-md-3 mb-2 col-lg-2  text-center ">
          <div class="col-md-12">
            <h3 class="fs-6">All Order(10)</h3>
          </div>
          <div class="col-md-12 mt-2">
            <button class="btn btn-primary btn-large">
              <i class="fa-solid fa-cart-plus cart-icon-small "></i>Add new
            </button>
          </div>
        </div>
        <div class="col-12 col-md-9 col-lg-10">
          <div class="row mb-1">
            <div class="col-12 d-flex justify-content-between flex-wrap">
              <button class="btn btn-primary me-1 mb-2">
                <i class="fas fa-user-plus pe-2"></i>Assign
              </button>
              <button class="btn btn-primary me-1 mb-2">
                <i class="fa-solid fa-arrow-circle-up pe-2"></i>Status
              </button>
              <button class="btn btn-primary me-1 mb-2">
                <i class="fa-solid fa-print pe-2"></i>Print
              </button>
              <button class="btn btn-primary me-1 mb-2">
                <i class="fa-solid fa-file-export pe-2"></i>Export
              </button>
              <button class="btn btn-primary me-1 mb-2">
                <i class="fa-solid fa-truck pe-2"></i>Steadfast
              </button>
              <button class="btn btn-primary me-1 mb-2">
                <i class="fa-solid fa-motorcycle pe-2"></i>Pathao
              </button>
              <button class="btn btn-primary me-1 mb-2">
                <i class="fa-solid fa-truck pe-2"></i>Redx
              </button>
              <button class="btn btn-primary me-1 mb-2">
                <i class="fa-solid fa-truck pe-2"></i>e-Courier
              </button>
            </div>
          </div>
          <div class="row mb-1">
            <div class="col-12 d-flex justify-content-between flex-wrap">
              <div class="dropdown me-1 mb-2">
                <button
                  class="btn btn-white dropdown-toggle"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                  border-radius="10px"
                  border="1px solid #000" 
                >
                  Date
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Today</a></li>
                  <li><a class="dropdown-item" href="#">This Week</a></li>
                  <li><a class="dropdown-item" href="#">This Month</a></li>
                </ul>
              </div>
              <div class="dropdown me-1 mb-2">
                <button
                  class="btn btn-white dropdown-toggle"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  Order Source
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Website</a></li>
                  <li><a class="dropdown-item" href="#">Mobile App</a></li>
                  <li><a class="dropdown-item" href="#">In-Store</a></li>
                </ul>
              </div>
              <div class="dropdown me-1 mb-2">
                <button
                  class="btn btn-white dropdown-toggle"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  Order Status
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Pending</a></li>
                  <li><a class="dropdown-item" href="#">Confirmed</a></li>
                  <li><a class="dropdown-item" href="#">Shipped</a></li>
                </ul>
              </div>
              <div class="dropdown me-1 mb-2">
                <button
                  class="btn btn-white dropdown-toggle"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  Assign
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Pending</a></li>
                  <li><a class="dropdown-item" href="#">Confirmed</a></li>
                  <li><a class="dropdown-item" href="#">Shipped</a></li>
                </ul>
              </div>
              <button class="btn btn-white serch-phone me-1 mb-2">
                Search phone Name Or Invoice Id
              </button>
              <button class="btn btn-primary mb-1">
                <i class="fa-solid fa-filter"></i> Filter
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-responsive table-striped">
          <thead class="table-primary text-center">
            <tr>
              <th class="action">Action</th>
              <th class="product">Product</th>
              <th class="order">Order</th>
              <th class="name">Name Phone</th>
              <th class="address">Address</th>
              <th class="assign">Assign</th>
              <th class="status">Status</th>
              <th class="combo">Comments</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="action-icons-row">
                <span class="action-icons">
                  <input type="checkbox">
                  <button class="dropdown-btn dropdown"></button>
                  <i class="fa-solid fa-eye"></i>  
                  <i class="fa-solid fa-pen"></i>
                  <i class="fa-solid fa-trash"></i>
                </span>
              </td>
              <td class="product-img">
                <img
                  src="./img/soot.jpeg"
                  alt="Product Image"
                  class="img-fluid"
                />
              </td>
              <td class="stacked-text">
                <span>ID 105</span>
                <span>৳105</span>
                <span>Website</span>
              </td>
              <td class="stacked-text">
                <span>Alamin</span>
                <span>+8801825659893</span>
                <span>T150,T152</span>
              </td>
              <td class="stacked-text">
                <span>west nakhalpara</span>
                <span>Tejgaon Dhaka</span>
                <span>Dhaka > Tejgaon</span>
                <!-- <p> <br> <br> </p> -->
              </td>
              <td class="stacked-text">
                <span>Steadfast</span>
                <span>link Copy</span>
                <span>Alamin</span>
              </td>
              <td class="stacked-text">
                <span>26-01-25</span>
                <span>02:49:26 pm</span>
                <span class="status-badge pending">Pending</span>
              </td>
              <td>
                <span>Good products</span>
              </td>
            </tr>
            <tr>
              <td class="action-icons-row">
                <span class="action-icons">
                  <input type="checkbox">
                  <button class="dropdown-btn dropdown"></button>
                  <i class="fa-solid fa-eye"></i>  
                  <i class="fa-solid fa-pen"></i>
                  <i class="fa-solid fa-trash"></i>
                </span>
              </td>
              <td class="product-img">
                <img
                  src="./img/soot.jpeg"
                  alt="Product Image"
                  class="img-fluid"
                />
              </td>
              <td class="stacked-text">
                <span>ID 105</span>
                <span>৳105</span>
                <span>Website</span>
              </td>
              <td class="stacked-text">
                <span>Alamin</span>
                <span>+8801825659893</span>
                <span>T150,T152</span>
              </td>
              <td class="stacked-text">
                <span>west nakhalpara</span>
                <span>Tejgaon Dhaka</span>
                <span>Dhaka > Tejgaon</span>
                <!-- <p> <br> <br> </p> -->
              </td>
              <td class="stacked-text">
                <span>Steadfast</span>
                <span>link Copy</span>
                <span>Alamin</span>
              </td>
              <td class="stacked-text">
                <span>26-01-25</span>
                <span>02:49:26 pm</span>
                <span class="status-badge processing">Processing</span>
              </td>
              <td>
                <span>Good products</span>
              </td>
            </tr>
            
            <tr>
              <td class="action-icons-row">
                <span class="action-icons">
                  <input type="checkbox">
                  <button class="dropdown-btn dropdown"></button>
                  <i class="fa-solid fa-eye"></i>  
                  <i class="fa-solid fa-pen"></i>
                  <i class="fa-solid fa-trash"></i>
                </span>
              </td>
              <td class="product-img">
                <img
                  src="./img/soot.jpeg"
                  alt="Product Image"
                  class="img-fluid"
                />
              </td>
              <td class="stacked-text">
                <span>ID 105</span>
                <span>৳105</span>
                <span>Website</span>
              </td>
              <td class="stacked-text">
                <span>Alamin</span>
                <span>+8801825659893</span>
                <span>T150,T152</span>
              </td>
              <td class="stacked-text">
                <span>west nakhalpara</span>
                <span>Tejgaon Dhaka</span>
                <span>Dhaka > Tejgaon</span>
                <!-- <p> <br> <br> </p> -->
              </td>
              <td class="stacked-text">
                <span>Steadfast</span>
                <span>link Copy</span>
                <span>Alamin</span>
              </td>
              <td class="stacked-text">
                <span>26-01-25</span>
                <span>02:49:26 pm</span>
                <span class="status-badge confirmed">Confirmed</span>
              </td>
              <td>
                <span>Good products</span>
              </td>
            </tr>
            <tr>
              <td class="action-icons-row">
                <span class="action-icons">
                  <input type="checkbox">
                  <button class="dropdown-btn dropdown"></button>
                  <i class="fa-solid fa-eye"></i>  
                  <i class="fa-solid fa-pen"></i>
                  <i class="fa-solid fa-trash"></i>
                </span>
              </td>
              <td class="product-img">
                <img
                  src="./img/soot.jpeg"
                  alt="Product Image"
                  class="img-fluid"
                />
              </td>
              <td class="stacked-text">
                <span>ID 105</span>
                <span>৳105</span>
                <span>Website</span>
              </td>
              <td class="stacked-text">
                <span>Alamin</span>
                <span>+8801825659893</span>
                <span>T150,T152</span>
              </td>
              <td class="stacked-text">
                <span>west nakhalpara</span>
                <span>Tejgaon Dhaka</span>
                <span>Dhaka > Tejgaon</span>
                <!-- <p> <br> <br> </p> -->
              </td>
              <td class="stacked-text">
                <span>Steadfast</span>
                <span>link Copy</span>
                <span>Alamin</span>
              </td>
              <td class="stacked-text">
                <span>26-01-25</span>
                <span>02:49:26 pm</span>
                <span class="status-badge shipping">Shipping</span>
              </td>
              <td>
                <span>Good products</span>
              </td>
            </tr>
            <tr>
              <td class="action-icons-row">
                <span class="action-icons">
                  <input type="checkbox">
                  <button class="dropdown-btn dropdown"></button>
                  <i class="fa-solid fa-eye"></i>  
                  <i class="fa-solid fa-pen"></i>
                  <i class="fa-solid fa-trash"></i>
                </span>
              </td>
              <td class="product-img">
                <img
                  src="./img/soot.jpeg"
                  alt="Product Image"
                  class="img-fluid"
                />
              </td>
              <td class="stacked-text">
                <span>ID 105</span>
                <span>৳105</span>
                <span>Website</span>
              </td>
              <td class="stacked-text">
                <span>Alamin</span>
                <span>+8801825659893</span>
                <span>T150,T152</span>
              </td>
              <td class="stacked-text">
                <span>west nakhalpara</span>
                <span>Tejgaon Dhaka</span>
                <span>Dhaka > Tejgaon</span>
                <!-- <p> <br> <br> </p> -->
              </td>
              <td class="stacked-text">
                <span>Steadfast</span>
                <span>link Copy</span>
                <span>Alamin</span>
              </td>
              <td class="stacked-text">
                <span>26-01-25</span>
                <span>02:49:26 pm</span>
                <span class="status-badge delivered">Delivered</span>
              </td>
              <td>
                <span>Good products</span>
              </td>
            </tr>
            <tr>
              <td class="action-icons-row">
                <span class="action-icons">
                  <input type="checkbox">
                  <button class="dropdown-btn dropdown"></button>
                  <i class="fa-solid fa-eye"></i>  
                  <i class="fa-solid fa-pen"></i>
                  <i class="fa-solid fa-trash"></i>
                </span>
              </td>
              <td class="product-img">
                <img
                  src="./img/soot.jpeg"
                  alt="Product Image"
                  class="img-fluid"
                />
              </td>
              <td class="stacked-text">
                <span>ID 105</span>
                <span>৳105</span>
                <span>Website</span>
              </td>
              <td class="stacked-text">
                <span>Alamin</span>
                <span>+8801825659893</span>
                <span>T150,T152</span>
              </td>
              <td class="stacked-text">
                <span>west nakhalpara</span>
                <span>Tejgaon Dhaka</span>
                <span>Dhaka > Tejgaon</span>
                <!-- <p> <br> <br> </p> -->
              </td>
              <td class="stacked-text">
                <span>Steadfast</span>
                <span>link Copy</span>
                <span>Alamin</span>
              </td>
              <td class="stacked-text">
                <span>26-01-25</span>
                <span>02:49:26 pm</span>
                <span class="status-badge return">Return</span>
              </td>
              <td>
                <span>Good products</span>
              </td>
            </tr>
            <tr>
              <td class="action-icons-row">
                <span class="action-icons">
                  <input type="checkbox">
                  <button class="dropdown-btn dropdown"></button>
                  <i class="fa-solid fa-eye"></i>  
                  <i class="fa-solid fa-pen"></i>
                  <i class="fa-solid fa-trash"></i>
                </span>
              </td>
              <td class="product-img">
                <img
                  src="./img/soot.jpeg"
                  alt="Product Image"
                  class="img-fluid"
                />
              </td>
              <td class="stacked-text">
                <span>ID 105</span>
                <span>৳105</span>
                <span>Website</span>
              </td>
              <td class="stacked-text">
                <span>Alamin</span>
                <span>+8801825659893</span>
                <span>T150,T152</span>
              </td>
              <td class="stacked-text">
                <span>west nakhalpara</span>
                <span>Tejgaon Dhaka</span>
                <span>Dhaka > Tejgaon</span>
                <!-- <p> <br> <br> </p> -->
              </td>
              <td class="stacked-text">
                <span>Steadfast</span>
                <span>link Copy</span>
                <span>Alamin</span>
              </td>
              <td class="stacked-text">
                <span>26-01-25</span>
                <span>02:49:26 pm</span>
                <span class="status-badge cancelled">Cancelled</span>
              </td>
              <td>
                <span>Good products</span>
              </td>
            </tr>
            <tr>
              <td class="action-icons-row">
                <span class="action-icons">
                  <input type="checkbox">
                  <button class="dropdown-btn dropdown"></button>
                  <i class="fa-solid fa-eye"></i>  
                  <i class="fa-solid fa-pen"></i>
                  <i class="fa-solid fa-trash"></i>
                </span>
              </td>
              <td class="product-img">
                <img
                  src="./img/soot.jpeg"
                  alt="Product Image"
                  class="img-fluid"
                />
              </td>
              <td class="stacked-text">
                <span>ID 105</span>
                <span>৳105</span>
                <span>Website</span>
              </td>
              <td class="stacked-text">
                <span>Alamin</span>
                <span>+8801825659893</span>
                <span>T150,T152</span>
              </td>
              <td class="stacked-text">
                <span>west nakhalpara</span>
                <span>Tejgaon Dhaka</span>
                <span>Dhaka > Tejgaon</span>
                <!-- <p> <br> <br> </p> -->
              </td>
              <td class="stacked-text">
                <span>Steadfast</span>
                <span>link Copy</span>
                <span>Alamin</span>
              </td>
              <td class="stacked-text">
                <span>26-01-25</span>
                <span>02:49:26 pm</span>
                <span class="status-badge on-the-way">On the way</span>
              </td>
              <td>
                <span>Good products</span>
              </td>
            </tr>

            <!-- Add more rows as needed -->
          </tbody>
        </table>
      </div>
    </div>
    
    
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".dropdown").forEach((button) => {
          button.addEventListener("click", function () {
            const row = this.closest("tr");
            const nextRow = row.nextElementSibling;

            // Close any open accordion rows
            document
              .querySelectorAll(".accordion-row")
              .forEach((accordionRow) => {
                accordionRow.remove();
              });

            if (!nextRow || !nextRow.classList.contains("accordion-row")) {
              const accordionRow = document.createElement("tr");
              accordionRow.classList.add("accordion-row");
              const accordionCell = document.createElement("td");
              accordionCell.colSpan = 9; // Adjust the colspan to match the number of columns
              accordionCell.innerHTML = `
              <div class="accordion-content">
                  <div class="left-panel">
                    <div class="form-grid-first-row">
                      <div class="form-group">
                          <label>Customer Name</label>
                          <input type="text" value="MD ALAMIN">
                      </div>
                      <div class="form-group">
                        <label>Delivery Partner</label>
                        <select>
                            <option>Steadfast</option>
                        </select>
                      </div>
                       <div class="form-group">
                    <label>Assign</label>
                    <select>
                        <option>Alamin</option>
                    </select>
                </div>
                      
                    </div>
                   
                    <div class="form-grid-2nd-row">
            
            <div class="left-section">
              <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" value="8801723546492">
                      </div>

                       <div class="form-group">
                    <label>Address District</label>
                    <input type="text" value="Dhaka">
                </div>
                
               
               
                <div class="form-group">
                        <label>Order Status</label>
                        <input type="text" value="Pending">
                      </div>
                <div class="form-group">
                    <label>Address Thana</label>
                    <input type="text" value="Tejgaon">
                </div>
            </div>
  
         
            <div class="right-section">
                <div class="form-group">
                    <label>Add Comment</label>
                    <textarea rows="3"></textarea>
                </div>
            </div>
        </div>
                      <div class="order-summary">
                        Delivered: 23 | Cancelled: 0 | Delivery Success Rate: 100%
                      </div>
                      <button class="update-btn">Update</button>
                   
                  </div>
                  <div class="right-panel">
                      <select>
                        <option>Add Product</option>
                      </select>
                    <table class="product-table">
                          <thead>
                              <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Size</th>
                                <th>Sell Price</th>
                                <th>Discount</th>
                                <th>Sub Total</th>
                                <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td><img src="shirt.jpg" alt="Shirt" width="50"></td>
                              <td>Men’s Premium </td>
                              <td>XL</td>
                              <td>1390</td>
                              <td><input type="text" value="100" style="width:50px;"></td>
                              <td>1290</td>
                              <td><button class="delete-btn">X</button></td>
                            </tr>
                        </tbody>
                    </table>
                 <div class="totals">
                      <span>Subtotal</span>
                      <span>Delivery Charge</span>
                      <span>Total</span>
                      <span>Advance</span>
                      <span>Due</span>
                  </div>
  
                  <div class="total-values">
                      <span>1290</span>
                      <input type="text" value="60">
                      <span>1350</span>
                      <span>300</span>
                      <span>1050</span>
                  </div>
                </div>
              </div>
            `;
              accordionRow.appendChild(accordionCell);
              row.parentNode.insertBefore(accordionRow, row.nextSibling);
            }
          });
        });
      });
    </script>

@endsection