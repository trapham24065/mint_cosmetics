@extends('admin.layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                        <h4 class="page-title">Dashboard</h4>
                        <div class="">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="#">Approx</a>
                                </li><!--end nav-item-->
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div><!--end row-->
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card  bg-welcome-img overflow-hidden">
                                <div class="card-body">
                                    <div class="">
                                        <h3 class="text-white fw-semibold fs-20 lh-base">Upgrade you plan for
                                            <br>Great experience</h3>
                                        <a href="#" class="btn btn-sm btn-danger">Upgarde Now</a>
                                        <img src="assets/images/extra/fund.png" alt="" class=" mb-n4 float-end"
                                             height="107">
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                        <div class="col-md-6">
                            <div class="card bg-globe-img">
                                <div class="card-body">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fs-16 fw-semibold">Balance</span>
                                            <form class="">
                                                <select id="dynamic-select" name="example-select"
                                                        data-placeholder="Select an option" data-dynamic-select>
                                                    <option value="1" data-img="assets/images/logos/m-card.png">xx25
                                                    </option>
                                                    <option value="2" data-img="assets/images/logos/ame-bank.png">xx56
                                                    </option>
                                                </select>
                                            </form>
                                        </div>

                                        <h4 class="my-2 fs-24 fw-semibold">122.5692.00 <small
                                                class="font-14">BTC</small>
                                        </h4>
                                        <p class="mb-3 text-muted fw-semibold">
                                            <span class="text-success"><i class="fas fa-arrow-up me-1"></i>11.1%</span>
                                            Outstanding balance boost
                                        </p>
                                        <button type="submit" class="btn btn-soft-primary">Transfer</button>
                                        <button type="button" class="btn btn-soft-danger">Request</button>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end col-->
                <div class="col-lg-5">
                    <div class="row justify-content-center">
                        <div class="col-md-6 col-lg-6">
                            <div class="card bg-corner-img">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-9">
                                            <p class="text-muted text-uppercase mb-0 fw-normal fs-13">Total Revenue</p>
                                            <h4 class="mt-1 mb-0 fw-medium">$8365.00</h4>
                                        </div>
                                        <!--end col-->
                                        <div class="col-3 align-self-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center thumb-md border-dashed border-primary rounded mx-auto">
                                                <i class="iconoir-dollar-circle fs-22 align-self-center mb-0 text-primary"></i>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </div>
                                <!--end card-body-->
                            </div>
                            <!--end card-->
                        </div>
                        <!--end col-->
                        <div class="col-md-6 col-lg-6">
                            <div class="card bg-corner-img">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-9">
                                            <p class="text-muted text-uppercase mb-0 fw-normal fs-13">New Order</p>
                                            <h4 class="mt-1 mb-0 fw-medium">722</h4>
                                        </div>
                                        <!--end col-->
                                        <div class="col-3 align-self-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center thumb-md border-dashed border-info rounded mx-auto">
                                                <i class="iconoir-cart fs-22 align-self-center mb-0 text-info"></i>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </div>
                                <!--end card-body-->
                            </div>
                            <!--end card-->
                        </div>
                        <!--end col-->
                        <div class="col-md-6 col-lg-6">
                            <div class="card bg-corner-img">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-9">
                                            <p class="text-muted text-uppercase mb-0 fw-normal fs-13">Sessions</p>
                                            <h4 class="mt-1 mb-0 fw-medium">181</h4>
                                        </div>
                                        <!--end col-->
                                        <div class="col-3 align-self-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center thumb-md border-dashed border-warning rounded mx-auto">
                                                <i class="iconoir-percentage-circle fs-22 align-self-center mb-0 text-warning"></i>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </div>
                                <!--end card-body-->
                            </div>
                            <!--end card-->
                        </div>
                        <!--end col-->

                        <div class="col-md-6 col-lg-6">
                            <div class="card bg-corner-img">
                                <div class="card-body">
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-9">
                                            <p class="text-muted text-uppercase mb-0 fw-normal fs-13">Avg. Order
                                                value</p>
                                            <h4 class="mt-1 mb-0 fw-medium">$1025.50</h4>
                                        </div>
                                        <!--end col-->
                                        <div class="col-3 align-self-center">
                                            <div
                                                class="d-flex justify-content-center align-items-center thumb-md border-dashed border-danger rounded mx-auto">
                                                <i class="iconoir-hexagon-dice fs-22 align-self-center mb-0 text-danger"></i>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </div>
                                <!--end card-body-->
                            </div>
                            <!--end card-->
                        </div><!--end col-->
                    </div>
                    <!--end row-->
                </div><!--end col-->

            </div><!--end row-->

            <div class="row justify-content-center">

                <div class="col-md-12 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="card-title">Report</h4>
                                </div><!--end col-->
                                <div class="col-auto">
                                    <div class="dropdown">
                                        <a href="#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown"
                                           aria-haspopup="true" aria-expanded="false">
                                            <i class="icofont-calendar fs-5 me-1"></i> This Month<i
                                                class="las la-angle-down ms-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Today</a>
                                            <a class="dropdown-item" href="#">Last Week</a>
                                            <a class="dropdown-item" href="#">Last Month</a>
                                            <a class="dropdown-item" href="#">This Year</a>
                                        </div>
                                    </div>
                                </div><!--end col-->
                            </div>  <!--end row-->
                        </div><!--end card-header-->
                        <div class="card-body pt-0">
                            <div id="reports" class="apex-charts pill-bar"></div>
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->
                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="card-title">Cash Flow</h4>
                                </div>
                                <!--end col-->
                                <div class="col-auto">
                                    <div class="dropdown">
                                        <a href="#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown"
                                           aria-haspopup="true" aria-expanded="false">
                                            <i class="icofont-calendar fs-5 me-1"></i>
                                            Weekly<i class="las la-angle-down ms-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Today</a>
                                            <a class="dropdown-item" href="#">Weekly</a>
                                            <a class="dropdown-item" href="#">Monthly</a>
                                            <a class="dropdown-item" href="#">Yearly</a>
                                        </div>
                                    </div>
                                </div> <!--end col-->
                            </div><!--end row-->
                        </div><!--end card-header-->
                        <div class="card-body pt-0">
                            <div id="cashflow" class="apex-charts"></div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="text-center">
                                        <p class="text-muted text-uppercase mb-0 fw-medium fs-13">Income</p>
                                        <h5 class="mt-1 mb-0 fw-medium">76%</h5>
                                    </div>
                                </div><!--end col-->
                                <div class="col-4">
                                    <div class="text-center">
                                        <p class="text-muted text-uppercase mb-0 fw-medium fs-13">Expense</p>
                                        <h5 class="mt-1 mb-0 fw-medium">23%</h5>
                                    </div>
                                </div><!--end col-->
                                <div class="col-4">
                                    <div class="text-center">
                                        <p class="text-muted text-uppercase mb-0 fw-medium fs-13">Other</p>
                                        <h5 class="mt-1 mb-0 fw-medium">1%</h5>
                                    </div>
                                </div><!--end col-->
                            </div><!--end row-->
                            <div class=" text-center mx-auto">
                                <img src="assets/images/extra/rabit.png" alt="" class="d-inline-block" height="105">
                            </div>
                            <div class="card-bg position-relative z-0">
                                <div class="p-3 bg-primary-subtle rounded position-relative">
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="flex-shrink-0 bg-primary-subtle text-primary thumb-lg rounded-circle">
                                            <i class="iconoir-bright-star fs-3"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h6 class="my-0 fw-normal text-dark fs-13 mb-0">You have $1.53 remaining
                                                found
                                                over ...<a href="#"
                                                           class="text-primary fw-medium mb-0 text-decoration-underline">View
                                                    Details</a></h6>

                                        </div><!--end media-body-->
                                    </div>
                                </div>
                            </div>
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div><!--end col-->
                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="card-title">Exchange Rate</h4>
                                </div><!--end col-->
                            </div>  <!--end row-->
                        </div><!--end card-header-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <tbody>
                                    <tr class="">
                                        <td class="px-0">
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/flags/us_flag.jpg"
                                                     class="me-2 align-self-center thumb-sm rounded-circle" alt="...">
                                                <h6 class="m-0 text-truncate">USA</h6>
                                            </div><!--end media-->
                                        </td>
                                        <td class="px-0 text-end"><span
                                                class="text-body ps-2 align-self-center text-end fw-medium">0.835230 <span
                                                    class="badge rounded text-success bg-success-subtle">1.10%</span></span>
                                        </td>
                                    </tr><!--end tr-->
                                    <tr class="">
                                        <td class="px-0">
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/flags/spain_flag.jpg"
                                                     class="me-2 align-self-center thumb-sm rounded-circle" alt="...">
                                                <h6 class="m-0 text-truncate">Spain</h6>
                                            </div><!--end media-->
                                        </td>
                                        <td class="px-0 text-end"><span
                                                class="text-body ps-2 align-self-center text-end fw-medium">0.896532 <span
                                                    class="badge rounded text-success bg-success-subtle">0.91%</span></span>
                                        </td>
                                    </tr><!--end tr-->
                                    <tr class="">
                                        <td class="px-0">
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/flags/french_flag.jpg"
                                                     class="me-2 align-self-center thumb-sm rounded-circle" alt="...">
                                                <h6 class="m-0 text-truncate">French</h6>
                                            </div><!--end media-->
                                        </td>
                                        <td class="px-0 text-end"><span
                                                class="text-body ps-2 align-self-center text-end fw-medium">0.875433 <span
                                                    class="badge rounded text-danger bg-danger-subtle">0.11%</span></span>
                                        </td>
                                    </tr><!--end tr-->
                                    <tr class="">
                                        <td class="px-0">
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/flags/germany_flag.jpg"
                                                     class="me-2 align-self-center thumb-sm rounded-circle" alt="...">
                                                <h6 class="m-0 text-truncate">Germany</h6>
                                            </div><!--end media-->
                                        </td>
                                        <td class="px-0 text-end"><span
                                                class="text-body ps-2 align-self-center text-end fw-medium">0.795621 <span
                                                    class="badge rounded text-success bg-success-subtle">0.85%</span></span>
                                        </td>
                                    </tr><!--end tr-->
                                    <tr class="">
                                        <td class="px-0">
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/flags/french_flag.jpg"
                                                     class="me-2 align-self-center thumb-sm rounded-circle" alt="...">
                                                <h6 class="m-0 text-truncate">French</h6>
                                            </div><!--end media-->
                                        </td>
                                        <td class="px-0 text-end"><span
                                                class="text-body ps-2 align-self-center text-end fw-medium">0.875433 <span
                                                    class="badge rounded text-danger bg-danger-subtle">0.11%</span></span>
                                        </td>
                                    </tr><!--end tr-->
                                    <tr class="">
                                        <td class="px-0 pb-0">
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/flags/baha_flag.jpg"
                                                     class="me-2 align-self-center thumb-sm rounded-circle" alt="...">
                                                <h6 class="m-0 text-truncate">Bahamas</h6>
                                            </div><!--end media-->
                                        </td>
                                        <td class="px-0 pb-0 text-end"><span
                                                class="text-body ps-2 align-self-center text-end fw-medium">0.845236 <span
                                                    class="badge rounded text-danger bg-danger-subtle">0.22%</span></span>
                                        </td>
                                    </tr><!--end tr-->
                                    </tbody>
                                </table> <!--end table-->
                            </div><!--end /div-->
                            <hr class="hr-dashed">
                            <div class="row">
                                <div class="col-lg-6 text-center">
                                    <div class="p-2 border-dashed border-theme-color rounded">
                                        <p class="text-muted text-uppercase mb-0 fw-normal fs-13">Higher Rate</p>
                                        <h5 class="mt-1 mb-0 fw-medium text-success">0.833658</h5>
                                        <small>05 Sep 2024</small>
                                    </div>
                                </div><!--end col-->
                                <div class="col-lg-6 text-center">
                                    <div class="p-2 border-dashed border-theme-color rounded">
                                        <p class="text-muted text-uppercase mb-0 fw-normal fs-13">Lower Rate</p>
                                        <h5 class="mt-1 mb-0 fw-medium text-danger">0.812547</h5>
                                        <small>05 Sep 2024</small>
                                    </div>
                                </div><!--end col-->
                            </div><!--end row-->
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div> <!--end col-->
            </div><!--end row-->

            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-3 order-2 order-lg-1">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="card-title">Balance Details</h4>
                                </div><!--end col-->
                                <div class="col-auto">
                                    <div class="p-2 border-dashed border-theme-color rounded">
                                        <h5 class="mt-1 mb-0 fw-medium">$82365.00</h5>
                                        <small class="text-muted">Available</small>
                                    </div>
                                </div><!--end col-->
                            </div>  <!--end row-->
                        </div>
                        <div class="card-body pt-0">
                            <div id="balance" class="apex-charts"></div>
                            <div class="bg-light py-3 px-2 mb-0 mt-3 text-center rounded">
                                <h6 class="mb-0"><i class="icofont-calendar fs-5 me-1"></i> 01 January 2024 to 31
                                    December
                                    2024</h6>
                            </div>
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div> <!--end col-->
                <div class="col-md-12 col-lg-6 order-1 order-lg-2">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="card-title">Transaction History</h4>
                                </div><!--end col-->
                                <div class="col-auto">
                                    <div class="dropdown">
                                        <a href="#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown"
                                           aria-haspopup="true" aria-expanded="false">
                                            <i class="icofont-calendar fs-5 me-1"></i> This Month<i
                                                class="las la-angle-down ms-1"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Today</a>
                                            <a class="dropdown-item" href="#">Last Week</a>
                                            <a class="dropdown-item" href="#">Last Month</a>
                                            <a class="dropdown-item" href="#">This Year</a>
                                        </div>
                                    </div>
                                </div><!--end col-->
                            </div>  <!--end row-->
                        </div><!--end card-header-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th class="border-top-0">Transaction</th>
                                        <th class="border-top-0">Date</th>
                                        <th class="border-top-0">AApprox</th>
                                        <th class="border-top-0">Status</th>
                                        <th class="border-top-0">Action</th>
                                    </tr><!--end tr-->
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/logos/lang-logo/chatgpt.png" height="40"
                                                     class="me-3 align-self-center rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0">Chat Gpt</h6>
                                                    <a href="#" class="fs-12 text-primary">ID: A3652</a>
                                                </div><!--end media body-->
                                            </div>
                                        </td>
                                        <td>20 july 2024</td>
                                        <td>$560</td>
                                        <td><span class="badge bg-success-subtle text-success fs-11 fw-medium px-2">Successful</span>
                                        </td>
                                        <td>
                                            <a href="#"><i class="las la-print text-secondary fs-18"></i></a>
                                            <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                        </td>
                                    </tr><!--end tr-->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/logos/lang-logo/gitlab.png" height="40"
                                                     class="me-3 align-self-center rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0">Gitlab</h6>
                                                    <a href="#" class="fs-12 text-primary">ID: B5784</a>
                                                </div><!--end media body-->
                                            </div>
                                        </td>
                                        <td>09 July 2024</td>
                                        <td>$2350</td>
                                        <td><span
                                                class="badge bg-warning-subtle text-warning fs-11 fw-medium px-2">Pending</span>
                                        </td>
                                        <td>
                                            <a href="#"><i class="las la-print text-secondary fs-18"></i></a>
                                            <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                        </td>
                                    </tr><!--end tr-->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/logos/lang-logo/nextjs.png" height="40"
                                                     class="me-3 align-self-center rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0">Nextjs</h6>
                                                    <a href="#" class="fs-12 text-primary">ID: C9632</a>
                                                </div><!--end media body-->
                                            </div>
                                        </td>
                                        <td>02 June 2024</td>
                                        <td>$2200</td>
                                        <td><span class="badge bg-success-subtle text-success fs-11 fw-medium px-2">Successful</span>
                                        </td>
                                        <td>
                                            <a href="#"><i class="las la-print text-secondary fs-18"></i></a>
                                            <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                        </td>
                                    </tr><!--end tr-->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/logos/lang-logo/vue.png" height="40"
                                                     class="me-3 align-self-center rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0">Vue</h6>
                                                    <a href="#" class="fs-12 text-primary">ID: D8596</a>
                                                </div><!--end media body-->
                                            </div>
                                        </td>
                                        <td>28 MAY 2024</td>
                                        <td>$1320</td>
                                        <td><span
                                                class="badge bg-danger-subtle text-danger fs-11 fw-medium px-2">Cancle</span>
                                        </td>
                                        <td>
                                            <a href="#"><i class="las la-print text-secondary fs-18"></i></a>
                                            <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                        </td>
                                    </tr><!--end tr-->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/logos/lang-logo/symfony.png" height="40"
                                                     class="me-3 align-self-center rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0">Symfony</h6>
                                                    <a href="#" class="fs-12 text-primary">ID: E7778</a>
                                                </div><!--end media body-->
                                            </div>
                                        </td>
                                        <td>15 May 2024</td>
                                        <td>$3650</td>
                                        <td><span class="badge bg-success-subtle text-success fs-11 fw-medium px-2">Successful</span>
                                        </td>
                                        <td>
                                            <a href="#"><i class="las la-print text-secondary fs-18"></i></a>
                                            <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                        </td>
                                    </tr><!--end tr-->
                                    </tbody>
                                </table> <!--end table-->
                            </div><!--end /div-->
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div> <!--end col-->
                <div class="col-md-6 col-lg-3 order-3 order-lg-3">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="card-title">Send Money</h4>
                                </div><!--end col-->
                                <div class="col-auto">
                                    <div class="dropdown">
                                        <a href="#" class="btn bt btn-light">
                                            <i class="icofont-contact-add fs-5 me-1"></i> Add Member
                                        </a>
                                    </div>
                                </div><!--end col-->
                            </div>  <!--end row-->
                        </div><!--end card-header-->
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <tbody>
                                    <tr class="">
                                        <td class="px-0">
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/users/avatar-1.jpg" height="36"
                                                     class="me-2 align-self-center rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0 text-truncate">Scott Holland</h6>
                                                    <a href="#"
                                                       class="font-12 text-muted text-decoration-underline">#3652</a>
                                                </div><!--end media body-->
                                            </div><!--end media-->
                                        </td>
                                        <td class="px-0 text-end"><span
                                                class="text-primary ps-2 align-self-center text-end fw-medium">$3325.00</span>
                                        </td>
                                        <td class="px-0 text-end"><a href="#" class="text-body"><i
                                                    class="las la-sync-alt"></i></a></td>
                                    </tr><!--end tr-->
                                    <tr class="">
                                        <td class="px-0">
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/users/avatar-2.jpg" height="36"
                                                     class="me-2 align-self-center rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0 text-truncate">Karen Savage</h6>
                                                    <a href="#"
                                                       class="font-12 text-muted text-decoration-underline">#4789</a>
                                                </div><!--end media body-->
                                            </div><!--end media-->
                                        </td>
                                        <td class="px-0 text-end"><span
                                                class="text-primary ps-2 align-self-center text-end fw-medium">$2548.00</span>
                                        </td>
                                        <td class="px-0 text-end"><a href="#" class="text-body"><i
                                                    class="las la-sync-alt"></i></a></td>
                                    </tr><!--end tr-->
                                    <tr class="">
                                        <td class="px-0">
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/users/avatar-3.jpg" height="36"
                                                     class="me-2 align-self-center rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0 text-truncate">Steven Sharp </h6>
                                                    <a href="#"
                                                       class="font-12 text-muted text-decoration-underline">#4521</a>
                                                </div><!--end media body-->
                                            </div><!--end media-->
                                        </td>
                                        <td class="px-0 text-end"><span
                                                class="text-primary ps-2 align-self-center text-end fw-medium">$2985.00</span>
                                        </td>
                                        <td class="px-0 text-end"><a href="#" class="text-body"><i
                                                    class="las la-sync-alt"></i></a></td>
                                    </tr><!--end tr-->
                                    <tr class="">
                                        <td class="px-0">
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/users/avatar-4.jpg" height="36"
                                                     class="me-2 align-self-center rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0 text-truncate">Teresa Himes </h6>
                                                    <a href="#"
                                                       class="font-12 text-muted text-decoration-underline">#3269</a>
                                                </div><!--end media body-->
                                            </div><!--end media-->
                                        </td>
                                        <td class="px-0 text-end"><span
                                                class="text-primary ps-2 align-self-center text-end fw-medium">$1845.00</span>
                                        </td>
                                        <td class="px-0 text-end"><a href="#" class="text-body"><i
                                                    class="las la-sync-alt"></i></a></td>
                                    </tr><!--end tr-->
                                    <tr>
                                        <td class="px-0">
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/users/avatar-5.jpg" height="36"
                                                     class="me-2 align-self-center rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0 text-truncate">Ralph Denton</h6>
                                                    <a href="#"
                                                       class="font-12 text-muted text-decoration-underline">#4521</a>
                                                </div><!--end media body-->
                                            </div><!--end media-->
                                        </td>
                                        <td class="px-0 text-end"><span
                                                class="text-primary ps-2 align-self-center text-end fw-medium">$1422.00</span>
                                        </td>
                                        <td class="px-0 text-end"><a href="#" class="text-body"><i
                                                    class="las la-sync-alt"></i></a></td>
                                    </tr><!--end tr-->
                                    <tr class="">
                                        <td class="px-0">
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/users/avatar-9.jpg" height="36"
                                                     class="me-2 align-self-center rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0 text-truncate">Steven Sharp </h6>
                                                    <a href="#"
                                                       class="font-12 text-muted text-decoration-underline">#4521</a>
                                                </div><!--end media body-->
                                            </div><!--end media-->
                                        </td>
                                        <td class="px-0 text-end"><span
                                                class="text-primary ps-2 align-self-center text-end fw-medium">$2985.00</span>
                                        </td>
                                        <td class="px-0 text-end"><a href="#" class="text-body"><i
                                                    class="las la-sync-alt"></i></a></td>
                                    </tr><!--end tr-->
                                    </tbody>
                                </table> <!--end table-->
                            </div><!--end /div-->
                        </div><!--end card-body-->
                    </div><!--end card-->
                </div> <!--end col-->
            </div><!--end row-->
        </div><!-- container -->

        <!--Start Rightbar-->
        <!--Start Rightbar/offcanvas-->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="Appearance" aria-labelledby="AppearanceLabel">
            <div class="offcanvas-header border-bottom justify-content-between">
                <h5 class="m-0 font-14" id="AppearanceLabel">Appearance</h5>
                <button type="button" class="btn-close text-reset p-0 m-0 align-self-center" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <h6>Account Settings</h6>
                <div class="p-2 text-start mt-3">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="settings-switch1">
                        <label class="form-check-label" for="settings-switch1">Auto updates</label>
                    </div><!--end form-switch-->
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="settings-switch2" checked>
                        <label class="form-check-label" for="settings-switch2">Location Permission</label>
                    </div><!--end form-switch-->
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="settings-switch3">
                        <label class="form-check-label" for="settings-switch3">Show offline Contacts</label>
                    </div><!--end form-switch-->
                </div><!--end /div-->
                <h6>General Settings</h6>
                <div class="p-2 text-start mt-3">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="settings-switch4">
                        <label class="form-check-label" for="settings-switch4">Show me Online</label>
                    </div><!--end form-switch-->
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="settings-switch5" checked>
                        <label class="form-check-label" for="settings-switch5">Status visible to all</label>
                    </div><!--end form-switch-->
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="settings-switch6">
                        <label class="form-check-label" for="settings-switch6">Notifications Popup</label>
                    </div><!--end form-switch-->
                </div><!--end /div-->
            </div><!--end offcanvas-body-->
        </div>
        <!--end Rightbar/offcanvas-->
        <!--end Rightbar-->
        <!--Start Footer-->

        <footer class="footer text-center text-sm-start d-print-none">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-0 rounded-bottom-0">
                            <div class="card-body">
                                <p class="text-muted mb-0">
                                    ©
                                    <script> document.write(new Date().getFullYear()); </script>
                                    Approx
                                    <span
                                        class="text-muted d-none d-sm-inline-block float-end">
                                            Design with
                                            <i class="iconoir-heart-solid text-danger align-middle"></i>
                                            by Mannatthemes</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <!--end footer-->
    </div>
@endsection
