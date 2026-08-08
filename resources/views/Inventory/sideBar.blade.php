<div id="mySidebar" class="sidebar">
    <!-- <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"> ×</a> -->
    <!-- <a class="navbar-brand font-weight-bold text-light " href="https://navieasoft.com/"><img src="{{url('public/upload/logo/logo.png')}}" alt=""></a> -->
      <div class="sidenav">
        <a class="nav-link btnLeft btn-1" href="{{route('branch.index')}}" style="{{($route=='branch.index')?'color:#89d6fb':''}}">
          <i class="fas fa-book" aria-hidden="true"></i>
            Branch Management
        </a>
        <a class="nav-link btnLeft btn-1" href="{{route('category.index')}}" style="{{($route=='category.index')?'color:#89d6fb':''}}">
          <i class="fas fa-book" aria-hidden="true"></i>
            Category Management
        </a>
        <a class="nav-link btnLeft btn-1" href="{{route('product.index')}}" style="{{($route=='product.index')?'color:#89d6fb':''}}">
          <i class="fas fa-book" aria-hidden="true"></i>
            Product Management
        </a>
        <a class="nav-link btnLeft btn-1" href="{{route('color.index')}}" style="{{($route=='color.index')?'color:#89d6fb':''}}">
          <i class="fas fa-book" aria-hidden="true"></i>
            color Management
        </a>
        <a class="nav-link btnLeft btn-1" href="{{route('size.index')}}" style="{{($route=='size.index')?'color:#89d6fb':''}}">
          <i class="fas fa-book" aria-hidden="true"></i>
            Size Management
        </a>
        <a class="nav-link btnLeft btn-1" href="{{route('brand.index')}}" style="{{($route=='brand.index')?'color:#89d6fb':''}}">
          <i class="fas fa-book" aria-hidden="true"></i>
            Brand Management
        </a>
        <a class="nav-link btnLeft btn-1" href="{{route('unit.index')}}" style="{{($route=='unit.index')?'color:#89d6fb':''}}">
          <i class="fas fa-book" aria-hidden="true"></i>
            Unit Management
        </a>

        <a class="nav-link btnLeft btn-1" href="{{route('purchase.index')}}" style="{{($route=='purchase.index')?'color:#89d6fb':''}}">
          <i class="fas fa-book" aria-hidden="true"></i>
            Purchase Management
        </a>



        <a class="nav-link btnLeft btn-1" href="{{route('invoice.index')}}" style="{{($route=='invoice.index')?'color:#89d6fb':''}}">
          <i class="fas fa-book" aria-hidden="true"></i>
            Invoice/Sale Management
        </a>


        <a class="nav-link btnLeft btn-1" href="{{route('quotation.index')}}" style="{{($route=='quotation.index')?'color:#89d6fb':''}}">
          <i class="fas fa-money-bill-wave" aria-hidden="true"></i>
          Quotation Management
        </a>

        <a class="nav-link btnLeft btn-1" href="{{route('vendor.index')}}" style="{{($route=='bonuspay.calculation')?'color:#89d6fb':''}}">
          <i class="fas fa-money-bill-wave" aria-hidden="true"></i>
            Vendor Management
        </a>
        <a class="nav-link btnLeft btn-1" href="{{route('customer.index')}}" style="{{($route=='customer.index')?'color:#89d6fb':''}}">
          <i class="fas fa-money-bill-wave" aria-hidden="true"></i>
          Customer Management
        </a>


        <a class="nav-link btnLeft btn-1" href="{{route('tax.index')}}" style="{{($route=='tax.index')?'color:#89d6fb':''}}">
          <i class="fas fa-book" aria-hidden="true"></i>
            Tax Management
        </a>



      </div>
    <script>
          var dropdown = document.getElementsByClassName("dropdown-btn");
          var i;

          for (i = 0; i < dropdown.length; i++) {
            dropdown[i].addEventListener("click", function() {
              this.classList.toggle("active");
              var dropdownContent = this.nextElementSibling;
              if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
              } else {
                dropdownContent.style.display = "block";
              }
            });
          }
    </script>


  </div>
