<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hr&Payroll</title>
    <!-- start bootstrap DataTable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <!-- bootstrap end dataTable -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('public/Dashboard/Sidebar.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="canonical" href="https://getbootstrap.com/docs/4.1/examples/dashboard/">

    <!-- Main Css -->

    <!-- stylesheet -->

    <link href="{{asset('public/Dashboard/style.css')}}" rel="stylesheet">
    <link href="{{asset('public/Dashboard/custom.css')}}" rel="stylesheet">
    <link href="{{asset('public/Dashboard/responsive.css')}}" rel="stylesheet">
    <link href="{{asset('public/Dashboard/common.css')}}" rel="stylesheet">
    <link href="{{asset('public/Dashboard/invoice.css')}}" rel="stylesheet">

    <!-- sweat alert-->
    <link rel="stylesheet" href="{{asset('public/Dashboard/SweetAlert/sweetAlertCss.css')}}">
    <!-- toaster -->
    <link rel="stylesheet" href="{{asset('public/Dashboard/Toaster/toasterCss.css')}}" />
    <!-- toaster js -->
    <script src="{{asset('public/Dashboard/Toaster/J3.2.1jquery.min.js')}}"></script>
    <script src="{{asset('public/Dashboard/Toaster/toastr.min.js')}}"></script>
    @yield('style')
    <style>
        @media screen and (min-width: 1000px) {
            .ledger {
              max-width: 1000px; /* New width for default modal */
            }
        }
    </style


</head>
<body>
