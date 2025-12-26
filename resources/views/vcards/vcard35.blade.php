<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Real Estate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/css/vcard35.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/slider/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slider/css/slick-theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/new_vcard/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/new_vcard/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/third-party.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-vcard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lightbox.css') }}">
</head>

<body>
    <div class="container p-0">
        <div class="main-content mx-auto w-100 overflow-hidden body-bg-black">
            <div class="banner-section position-relative w-100">
                <div class="banner-bg-img text-md-start text-end">
                    <img src="{{ asset('assets/img/vcard35/banner-bg.png') }}" alt="bg-vector" />
                </div>
                <div class="banner-text-img mb-40">
                    <img src="{{ asset('assets/img/vcard35/banner-text-img.png') }}" class="h-100" />
                </div>
                <div class="banner-img ms-auto">
                    <img src="{{ asset('assets/img/vcard35/banner.png') }}" class="object-fit-cover w-100 h-100" />
                </div>
                <div class="d-flex justify-content-end position-absolute top-0 end-0 me-3">
                    <div class="language pt-3 me-2">
                        <ul class="text-decoration-none">
                            <li class="dropdown1 dropdown lang-list">
                                <a class="dropdown-toggle lang-head text-decoration-none" data-toggle="dropdown"
                                    role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-language me-2"></i>Language</a>
                                <ul class="dropdown-menu start-0 lang-hover-list top-100 mt-0">
                                    <li>
                                        <img src="{{ asset('assets/img/vcard1/english.png') }}" width="25px"
                                            height="20px" class="me-3" loading="lazy"><a href="#">English</a>
                                    </li>
                                    <li>
                                        <img src="{{ asset('assets/img/vcard1/spain.png') }}" width="25px"
                                            height="20px" class="me-3" loading="lazy"><a href="#">Spanish</a>
                                    </li>
                                    <li>
                                        <img src="{{ asset('assets/img/vcard1/france.png') }}" width="25px"
                                            height="20px" class="me-3" loading="lazy"><a href="#">Franch</a>
                                    </li>
                                    <li>
                                        <img src="{{ asset('assets/img/vcard1/arabic.svg') }}" width="25px"
                                            height="20px" class="me-3" loading="lazy"><a href="#">Arabic</a>
                                    </li>
                                    <li>
                                        <img src="{{ asset('assets/img/vcard1/german.png') }}" width="25px"
                                            height="20px" class="me-3" loading="lazy"><a href="#">German</a>
                                    </li>
                                    <li>
                                        <img src="{{ asset('assets/img/vcard1/russian.jpeg') }}" width="25px"
                                            height="20px" class="me-3" loading="lazy"><a href="#">russian</a>
                                    </li>
                                    <li>
                                        <img src="{{ asset('assets/img/vcard1/turkish.png') }}" width="25px"
                                            height="20px" class="me-3" loading="lazy"><a href="#">Turkish</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="profile-section px-30">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="card flex-sm-row">
                            <div class="card-img d-flex justify-content-center align-items-center">
                                <img src="{{ asset('assets/img/vcard35/profile-img.png') }}"
                                    class="img-fluid h-100 object-fit-cover" />
                            </div>
                            <div class="card-body text-sm-start text-center p-0">
                                <div class="profile-name">
                                    <h2 class="text-white fs-22 fw-bold mb-sm-2 mb-1">
                                        Robert Johnson
                                    </h2>
                                    <em class="fs-16 text-gray-300 mb-0">Real Estate Agent</em>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="social-media mt-sm-0 mt-40 mb-40 d-flex flex-column gap-3 flex-wrap  justify-content-sm-start justify-content-center">
                    <div
                        class="ps-2 pe-sm-4 pe-3 border-right-gray-200 social-icon-bg p-2 social-box position-relative d-flex justify-content-center align-items-center">
                        <a href="" class="social-icon d-flex justify-content-center align-items-center">
                            <svg width="12" height="24" viewBox="0 0 12 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3.3487 13.5467C2.81637 13.5467 2.32761 13.5467 1.83884 13.5467C1.42198 13.5467 1.00512 13.5545 0.588982 13.5436C0.153237 13.5326 0.0108936 13.3915 0.00726242 12.9253C-0.00217873 11.7036 -0.00290497 10.4811 0.00798866 9.25947C0.0123461 8.79713 0.164857 8.65059 0.594792 8.64746C1.40092 8.64197 2.20705 8.64589 3.0139 8.64589C3.11267 8.64589 3.21144 8.64589 3.3487 8.64589C3.3487 8.51346 3.34725 8.39983 3.3487 8.28699C3.36758 7.17345 3.32837 6.05522 3.41697 4.94795C3.64138 2.12768 5.63636 0.11376 8.25955 0.0220753C9.32712 -0.0155388 10.3976 0.00561912 11.4659 0.00875362C11.8421 0.00953725 11.9946 0.175666 11.9961 0.583152C12.0011 1.72646 12.0011 2.87056 11.9968 4.01387C11.9953 4.45192 11.853 4.61099 11.4354 4.62118C10.7019 4.63999 9.9684 4.63999 9.23417 4.65253C8.41133 4.66585 8.06564 5.01299 8.04821 5.88987C8.03078 6.77616 8.04458 7.664 8.04458 8.58477C8.16877 8.58477 8.26536 8.58477 8.36195 8.58477C9.29517 8.58477 10.2284 8.5832 11.1616 8.58555C11.7499 8.58633 11.8879 8.73365 11.8886 9.35742C11.8893 10.5203 11.8908 11.684 11.8879 12.8469C11.8864 13.3672 11.7593 13.5075 11.2742 13.5099C10.2146 13.5146 9.155 13.5114 8.04531 13.5114C8.04531 13.6478 8.04531 13.7692 8.04531 13.8899C8.04531 16.9492 8.04531 20.0093 8.04531 23.0685C8.04531 23.9117 7.96252 23.9995 7.16801 23.9995C6.12585 23.9995 5.08442 24.0011 4.04226 23.9987C3.48451 23.9979 3.3487 23.853 3.3487 23.2574C3.34798 20.1487 3.34797 17.0401 3.34797 13.9322C3.3487 13.817 3.3487 13.7018 3.3487 13.5467Z"
                                    fill="#C8B0DB" />
                            </svg>
                        </a>
                        <p class="fs-16 mb-0 text-gray-300 fw-bold">Facebook</p>
                    </div>
                    <div
                        class="ps-2 pe-sm-4 pe-3 border-right-gray-200 social-icon-bg p-2 social-box position-relative d-flex justify-content-center align-items-center">
                        <a href="" class="social-icon d-flex justify-content-center align-items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M24 10.7766C24 11.2458 24 11.7143 24 12.1835C23.9623 12.4924 23.9317 12.8018 23.8865 13.1095C23.5201 15.598 22.4649 17.7512 20.6982 19.5444C18.8044 21.4669 16.5089 22.6001 13.827 22.9108C11.4821 23.1826 9.24958 22.7716 7.15112 21.6789C7.00644 21.6037 6.88352 21.5743 6.71472 21.6348C5.47258 22.0799 4.22456 22.508 2.97949 22.9454C1.98554 23.2942 0.993358 23.6483 0 24C0.0205847 23.9019 0.0299948 23.8003 0.0629303 23.7064C0.798097 21.6008 1.53209 19.4939 2.27902 17.3924C2.36665 17.1464 2.36018 16.9555 2.23785 16.7172C1.1104 14.5234 0.739284 12.1941 1.10099 9.76255C1.46445 7.31869 2.52015 5.19544 4.26926 3.44445C6.86352 0.847929 10.0165 -0.274179 13.6653 0.0564058C15.8831 0.257223 17.8775 1.07576 19.6184 2.47032C21.8856 4.28589 23.3036 6.61818 23.8176 9.48364C23.8941 9.91169 23.94 10.3456 24 10.7766ZM9.87829 9.47248C9.893 9.44664 9.89535 9.43842 9.90064 9.43314C10.2035 9.1278 10.5082 8.82482 10.8087 8.51772C11.1616 8.1566 11.1616 7.76847 10.8052 7.40853C10.2935 6.8924 9.77949 6.37802 9.26193 5.86717C8.89729 5.50723 8.51323 5.50723 8.14977 5.86482C7.77101 6.23769 7.40637 6.62523 7.0182 6.98811C6.57886 7.39914 6.4483 7.90235 6.5077 8.47662C6.60709 9.43138 7.00114 10.2799 7.48812 11.0849C8.75437 13.1776 10.4341 14.8787 12.4731 16.2245C13.2977 16.7688 14.1734 17.2151 15.1556 17.4135C15.9108 17.5662 16.5765 17.4576 17.1129 16.8375C17.4188 16.484 17.7734 16.1722 18.1039 15.8393C18.4827 15.4571 18.4821 15.0789 18.1004 14.6943C17.6158 14.2064 17.1276 13.7213 16.6407 13.2357C16.1937 12.7901 15.8408 12.7889 15.3962 13.2322C15.1044 13.5235 14.8139 13.8159 14.5257 14.1048C12.4326 13.0801 10.8946 11.5441 9.87829 9.47248Z"
                                    fill="#C8B0DB" />
                            </svg>
                        </a>
                        <p class="fs-16 mb-0 text-gray-300 fw-bold">Whatsapp</p>
                    </div>
                    <div
                        class="ps-2 pe-sm-4 pe-3 border-right-gray-200 social-icon-bg p-2 social-box position-relative d-flex justify-content-center align-items-center">
                        <a href="" class="social-icon d-flex justify-content-center align-items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M23.9835 14.5311C23.9757 13.4172 23.8646 12.3096 23.5407 11.2334C23.1355 9.88547 22.401 8.80137 21.0907 8.18299C20.0785 7.70552 18.999 7.57167 17.8961 7.60454C15.9655 7.66247 14.4034 8.40764 13.3591 10.0921C13.3473 10.1109 13.3161 10.118 13.2503 10.1555C13.2503 9.41582 13.2503 8.709 13.2503 8.0014C11.6444 8.0014 10.0776 8.0014 8.51466 8.0014C8.51466 13.3476 8.51466 18.6726 8.51466 24C10.1691 24 11.7969 24 13.467 24C13.467 23.8536 13.467 23.7378 13.467 23.6212C13.467 21.1211 13.46 18.6202 13.4725 16.1201C13.4748 15.5651 13.5108 15.0062 13.5898 14.4575C13.8057 12.9531 14.5708 12.1398 15.96 11.9754C17.4862 11.7954 18.5516 12.3519 18.8535 13.9902C18.9607 14.5726 19.0076 15.1729 19.0116 15.7655C19.0288 18.383 19.0209 21.0005 19.0217 23.618C19.0217 23.7307 19.0217 23.8427 19.0217 23.9499C20.7035 23.9499 22.3384 23.9499 23.9882 23.9499C23.9929 23.8842 23.9999 23.8372 23.9999 23.7895C23.996 20.7039 24.0038 17.6175 23.9835 14.5311Z"
                                    fill="#C8B0DB" />
                                <path
                                    d="M0.412536 23.989C2.0701 23.989 3.71203 23.989 5.35943 23.989C5.35943 18.6499 5.35943 13.3327 5.35943 7.99747C3.70812 7.99747 2.07323 7.99747 0.412536 7.99747C0.412536 13.3429 0.412536 18.6609 0.412536 23.989Z"
                                    fill="#C8B0DB" />
                                <path
                                    d="M2.86144 0.000114328C1.28835 0.0142038 -0.000781885 1.31357 3.55808e-07 2.88376C0.000782597 4.48057 1.31886 5.80185 2.90133 5.79246C4.4752 5.78307 5.7659 4.46414 5.75651 2.87281C5.74791 1.276 4.44782 -0.0139752 2.86144 0.000114328Z"
                                    fill="#C8B0DB" />
                            </svg>
                        </a>
                        <p class="fs-16 mb-0 text-gray-300 fw-bold">linkedin</p>
                    </div>
                    <div
                        class="ps-2 pe-sm-4 pe-3 border-right-gray-200 social-icon-bg p-2 social-box position-relative d-flex justify-content-center align-items-center">
                        <a href="" class="social-icon d-flex justify-content-center align-items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_4578_1230)">
                                    <path
                                        d="M23.9973 5.5308C23.9973 9.84287 23.9973 14.1542 23.9973 18.4663C23.9026 18.9146 23.8468 19.3761 23.7067 19.8097C22.8893 22.3315 20.6214 23.9861 17.9602 23.9934C13.9849 24.0044 10.0096 23.9993 6.03433 23.9949C4.75765 23.9934 3.58077 23.6405 2.54695 22.8847C0.838854 21.636 0.000946106 19.9374 0.000946106 17.8235C0.000212386 13.9576 -0.00198877 10.0916 0.00461471 6.22563C0.00534843 5.82942 0.0236914 5.42588 0.0948623 5.037C0.497675 2.8197 1.75747 1.27302 3.8383 0.426307C4.37172 0.209125 4.9653 0.137955 5.53099 1.52588e-05C9.84307 1.52588e-05 14.1544 1.52588e-05 18.4665 1.52588e-05C18.6345 0.0300978 18.8033 0.0579791 18.9705 0.0895291C21.1732 0.505548 22.7155 1.75287 23.5636 3.81829C23.7859 4.35758 23.8571 4.9585 23.9973 5.5308ZM21.9832 11.9985C21.9861 11.9985 21.9891 11.9985 21.992 11.9985C21.992 10.0461 21.9883 8.09368 21.9942 6.14052C21.9957 5.51466 21.912 4.90714 21.6325 4.34437C20.8753 2.81897 19.6404 2.01261 17.9331 2.00674C13.9732 1.9928 10.0133 2.0016 6.0534 2.00454C5.82008 2.00454 5.58529 2.02655 5.3549 2.05957C3.53748 2.31564 2.03262 3.95917 2.02088 5.79934C1.99373 9.88396 2.00987 13.9686 2.00767 18.0532C2.00767 18.6079 2.11626 19.1435 2.36059 19.6395C3.12806 21.1943 4.38639 21.9881 6.12311 21.9903C10.036 21.9962 13.949 21.9933 17.8619 21.9896C18.126 21.9896 18.3924 21.9691 18.6543 21.9331C20.4365 21.6888 21.9524 20.0269 21.9766 18.23C22.0052 16.1536 21.9832 14.0764 21.9832 11.9985Z"
                                        fill="#C8B0DB" />
                                    <path
                                        d="M17.9966 12.0279C17.965 15.3502 15.2437 18.0393 11.9566 17.996C8.638 17.9527 5.96873 15.2519 6.00028 11.9677C6.03183 8.64397 8.75099 5.95782 12.0395 5.99964C15.3589 6.04293 18.0281 8.74376 17.9966 12.0279ZM12.0021 8.00417C9.79508 8.0005 8.0136 9.7761 8.0048 11.989C7.99526 14.1931 9.7694 15.9782 11.9838 15.9929C14.1945 16.0069 15.995 14.2122 15.9928 11.9963C15.9906 9.78637 14.2121 8.00783 12.0021 8.00417Z"
                                        fill="#C8B0DB" />
                                    <path
                                        d="M18.51 6.99091C17.675 6.99677 17.0059 6.33276 17.0059 5.49852C17.0066 4.67749 17.6633 4.01347 18.4872 4.00026C19.3009 3.98706 19.9994 4.67969 19.9987 5.49925C19.9987 6.31588 19.3303 6.98504 18.51 6.99091Z"
                                        fill="#C8B0DB" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_4578_1230">
                                        <rect width="24" height="24" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </a>
                        <p class="fs-16 mb-0 text-gray-300 fw-bold">Instagram</p>
                    </div>
                    <div
                        class="ps-2 pe-sm-4 pe-3 social-icon-bg p-2 social-box position-relative d-flex justify-content-center align-items-center">
                        <a href="" class="social-icon d-flex justify-content-center align-items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_4578_1276)">
                                    <path
                                        d="M7.73864 17.8765C5.59857 17.5841 4.17871 16.5227 3.37619 14.4C4.06554 14.4 4.67258 14.4 5.26933 14.4C3.09839 13.5011 1.87402 11.9307 1.71969 9.43971C2.37818 9.63465 2.98521 9.81877 3.59225 9.99205C3.62312 9.94873 3.65399 9.90541 3.68485 9.86209C2.77944 9.09314 2.13124 8.14007 1.90489 6.92707C1.67854 5.71408 1.78143 4.54441 2.40904 3.34224C5.00182 6.45054 8.17077 8.2592 12.0702 8.51913C12.0702 8.06426 12.0599 7.67437 12.0702 7.28448C12.132 5.21589 13.0991 3.71047 14.8585 2.87654C16.5767 2.06427 18.2641 2.28087 19.7457 3.55885C20.1572 3.91625 20.5276 3.99206 20.9906 3.79712C21.7005 3.5047 22.4105 3.21228 23.1821 2.90903C22.8529 3.99206 22.1738 4.72852 21.4639 5.52997C22.2047 5.32419 22.9455 5.10758 23.6863 4.90181C23.7171 4.9343 23.7583 4.96679 23.7892 4.99928C23.2027 5.63827 22.6471 6.33141 22.0092 6.90541C21.6594 7.21949 21.5256 7.53357 21.5153 7.99928C21.4536 11.2592 20.5276 14.2159 18.6139 16.7935C16.0417 20.27 12.6258 22.0245 8.4177 22.1112C6.01012 22.1653 3.75687 21.6346 1.63738 20.4325C1.43161 20.3133 1.22583 20.1834 1.00977 19.9993C3.44821 20.1184 5.67059 19.5119 7.73864 17.8765Z"
                                        fill="#C8B0DB" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_4578_1276">
                                        <rect width="22.8" height="19.7112" fill="white"
                                            transform="translate(1 2.39999)" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </a>
                        <p class="fs-16 mb-0 text-gray-300 fw-bold">Twitter</p>
                    </div>
                </div>
                <div class="desc">
                    <p class="text-gray-300 fs-14 text-sm-start text-center mb-0">
                        Lorem Ipsum is simply dummy text of the printing and typesetting
                        industry. Lorem Ipsum has been the industry's standard dummy text
                        ever since the 1500s, when an unknown printer took a galley of
                        type and scrambled it to make a type specimen book.
                    </p>
                </div>
            </div>
            <div class="contact-section position-relative pt-60 px-30">
                <div class="contact-bg-img text-end">
                    <img src="{{ asset('assets/img/vcard35/contact-bg.png') }}" alt="bg-vector" />
                </div>
                <div class="section-heading left-heading">
                    <h2>Contact.</h2>
                </div>
                <div class="row align-items-center">
                    <div class="col-12 col-sm-6 mb-4">
                        <div class="contact-box d-flex align-items-center">
                            <div class="contact-icon d-flex justify-content-center align-items-center">
                                <svg width="22" height="18" viewBox="0 0 22 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_4578_1323)">
                                        <path
                                            d="M11.0213 0.666245C13.7803 0.666245 16.5337 0.660688 19.2927 0.671803C19.6536 0.671803 20.0199 0.710705 20.3697 0.788508C20.997 0.933001 21.43 1.33313 21.702 1.9111C21.9407 2.41683 21.863 2.6947 21.3967 2.98924C20.7916 3.3727 20.1809 3.75061 19.5758 4.13407C16.9334 5.81796 14.291 7.49074 11.6597 9.18575C11.1823 9.49697 10.8048 9.48585 10.3274 9.18019C7.12431 7.11839 3.90456 5.08438 0.69037 3.03926C0.623755 2.9948 0.551588 2.9559 0.490524 2.91144C0.174101 2.6947 0.113037 2.52242 0.224062 2.16119C0.496075 1.272 1.17333 0.755164 2.16701 0.69959C2.5667 0.67736 2.9664 0.67736 3.36054 0.671803C5.91413 0.671803 8.46772 0.671803 11.0213 0.666245C11.0213 0.671803 11.0213 0.666245 11.0213 0.666245Z"
                                            fill="#C8B0DB" />
                                        <path
                                            d="M10.9746 17.3333C8.17122 17.3333 5.37337 17.3389 2.56997 17.3278C2.22024 17.3278 1.8594 17.2889 1.52078 17.1944C0.837968 16.9999 0.41607 16.5164 0.210673 15.8439C0.105198 15.4938 0.166262 15.3216 0.482686 15.1215C3.06958 13.4932 5.65648 11.8648 8.24338 10.2365C8.5154 10.0642 8.80406 10.0642 9.07608 10.2254C9.56459 10.5144 10.042 10.8145 10.5194 11.1201C10.9136 11.3758 11.0801 11.3813 11.4631 11.1313C11.9683 10.8034 12.479 10.4866 12.9842 10.1587C13.2562 9.98088 13.5004 10.0365 13.7503 10.1976C14.5774 10.7311 15.4045 11.2591 16.2372 11.787C17.9359 12.8652 19.6402 13.9322 21.3389 15.0103C21.8607 15.3382 21.944 15.6105 21.6886 16.1607C21.3389 16.9054 20.706 17.2222 19.9344 17.3167C19.7401 17.3389 19.5458 17.3389 19.3515 17.3389C16.5592 17.3333 13.7669 17.3333 10.9746 17.3333Z"
                                            fill="#C8B0DB" />
                                        <path
                                            d="M0.206055 13.4709C0.206055 10.4811 0.206055 7.54118 0.206055 4.5513C2.5487 6.04624 4.85803 7.5134 7.20623 9.00834C4.86358 10.5033 2.55425 11.976 0.206055 13.4709Z"
                                            fill="#C8B0DB" />
                                        <path
                                            d="M14.8115 9.00834C17.1486 7.51896 19.458 6.04624 21.8006 4.5513C21.8006 7.53007 21.8006 10.4644 21.8006 13.4598C19.4635 11.976 17.1542 10.5033 14.8115 9.00834Z"
                                            fill="#C8B0DB" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_4578_1323">
                                            <rect width="21.6667" height="16.6667" fill="white"
                                                transform="translate(0.166992 0.666656)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <div class="contact-desc">
                                <a href="braxtonreyes@gmail.com" class="text-white fw-5 mb-0">robert@gmail.com</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 mb-4">
                        <div class="contact-box d-flex align-items-center">
                            <div class="contact-icon d-flex justify-content-center align-items-center">
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M4.54095 9.54438C6.32071 12.958 8.84849 15.5472 12.1888 17.3248C12.3306 17.4021 12.666 17.3119 12.8078 17.1831C13.5687 16.4617 14.3167 15.7275 15.039 14.9675C15.4388 14.5424 15.8773 14.478 16.4318 14.581C17.7473 14.8129 19.0757 15.0448 20.404 15.1994C21.4616 15.3282 21.8356 15.676 21.8356 16.758C21.8356 17.9174 21.8356 19.0638 21.8356 20.2231C21.8356 21.4726 21.4358 21.8591 20.159 21.8333C11.0796 21.6787 2.95464 15.238 0.81377 6.4013C0.439762 4.84264 0.323691 3.20669 0.181825 1.5965C0.0915477 0.643272 0.620317 0.179538 1.57468 0.179538C2.83857 0.166656 4.10246 0.166656 5.36635 0.166656C6.29492 0.166656 6.68183 0.591746 6.7979 1.51921C6.97845 2.88465 7.22349 4.25009 7.48143 5.60265C7.5846 6.18231 7.48143 6.64605 7.05583 7.05825C6.20464 7.88267 5.37925 8.70709 4.54095 9.54438Z"
                                        fill="#C8B0DB" />
                                </svg>
                            </div>
                            <div class="contact-desc">
                                <a href="tel:+1 4078461474" class="text-white fw-5 mb-0">+1 4078461474</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 mb-sm-0 mb-4">
                        <div class="contact-box d-flex align-items-center">
                            <div class="contact-icon d-flex justify-content-center align-items-center">
                                <svg width="26" height="22" viewBox="0 0 26 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_4578_1307)">
                                        <path
                                            d="M0.824791 21.8334C0.719793 21.6346 0.551797 21.4463 0.520297 21.237C0.467798 20.8604 0.520297 20.4733 0.499298 20.0967C0.488798 19.7514 0.635795 19.5736 0.992788 19.5841C1.09779 19.5841 1.20278 19.5841 1.30778 19.5841C9.10913 19.5841 16.9 19.5841 24.7013 19.5945C24.9638 19.5945 25.2263 19.6991 25.4888 19.7514C25.4888 20.4419 25.4888 21.1429 25.4888 21.8334C17.278 21.8334 9.04613 21.8334 0.824791 21.8334Z"
                                            fill="#C8B0DB" />
                                        <path
                                            d="M13.0253 9.03838C15.8287 9.03838 18.6322 9.03838 21.4461 9.03838C23.0316 9.03838 23.8086 9.82302 23.8191 11.4132C23.8191 11.4342 23.8191 11.4446 23.8191 11.4655C24.0081 12.3862 23.5776 12.9093 22.7586 13.265C21.9291 13.6207 21.1311 13.6939 20.4066 13.0767C20.0497 12.7733 19.7557 12.4071 19.4617 12.041C19.0732 11.5702 18.7057 11.5597 18.3067 12.02C18.0442 12.3234 17.7817 12.6268 17.4877 12.8884C16.6582 13.6312 15.7447 13.7149 14.8103 13.1185C14.3903 12.8465 14.0123 12.5013 13.6448 12.156C13.1408 11.6852 12.9203 11.6852 12.4268 12.177C12.1433 12.4594 11.8493 12.7314 11.5238 12.9616C10.4213 13.7567 9.42387 13.7044 8.39489 12.8256C8.23739 12.6896 8.09039 12.5431 7.9539 12.3862C7.25041 11.5806 7.10341 11.5806 6.39993 12.4176C5.44445 13.5475 4.23697 13.7986 2.893 13.1918C2.36801 12.9511 2.16851 12.585 2.22101 12.041C2.25251 11.7794 2.28401 11.5179 2.25251 11.2668C2.07402 9.97995 3.11349 9.01745 4.45747 9.02792C7.30291 9.0593 10.1589 9.03838 13.0253 9.03838Z"
                                            fill="#C8B0DB" />
                                        <path
                                            d="M23.6109 18.1717C16.5446 18.1717 9.50972 18.1717 2.44336 18.1717C2.44336 17.0732 2.44336 15.9747 2.44336 14.8343C4.19682 15.4202 5.72979 15.0435 6.98977 13.694C9.08973 15.7026 11.0217 15.4306 13.0481 13.6312C15.5576 15.8073 17.3636 15.3051 19.075 13.5998C19.6525 14.217 20.2825 14.782 21.1435 15.0017C21.994 15.2214 22.792 15.0435 23.6319 14.6878C23.6109 15.8596 23.6109 16.9895 23.6109 18.1717Z"
                                            fill="#C8B0DB" />
                                        <path
                                            d="M11.5241 8.05495C11.5241 7.01922 11.5136 6.00442 11.5346 4.97915C11.5451 4.64437 11.7866 4.44559 12.1331 4.44559C12.7106 4.43513 13.2881 4.43513 13.8551 4.44559C14.2541 4.44559 14.485 4.67575 14.485 5.06284C14.506 6.04626 14.4955 7.04015 14.4955 8.05495C13.4981 8.05495 12.5321 8.05495 11.5241 8.05495Z"
                                            fill="#C8B0DB" />
                                        <path
                                            d="M13.0162 0.166656C13.2682 0.626982 13.5621 1.15008 13.8456 1.68364C13.9716 1.92426 14.1396 2.16489 14.2131 2.42643C14.3706 3.00184 14.1186 3.62956 13.6671 3.88064C13.2157 4.13173 12.5227 4.0585 12.1552 3.72371C11.7667 3.35755 11.6407 2.6566 11.9137 2.14396C12.2707 1.45347 12.6592 0.783911 13.0162 0.166656Z"
                                            fill="#C8B0DB" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_4578_1307">
                                            <rect width="25" height="21.6667" fill="white"
                                                transform="translate(0.5 0.166656)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <div class="contact-desc">
                                <p class="text-white fw-5 mb-0">12th June, 1990</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="contact-box d-flex align-items-center">
                            <div class="contact-icon d-flex justify-content-center align-items-center">
                                <svg width="20" height="26" viewBox="0 0 20 26" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_4578_1300)">
                                        <path
                                            d="M9.61002 21.005C9.28803 20.6277 8.97867 20.2698 8.67563 19.9086C7.04044 17.9416 5.49364 15.9069 4.15519 13.7175C3.55226 12.734 3.02508 11.7118 2.57367 10.6444C1.74345 8.67424 1.8855 6.73628 2.80096 4.85959C3.98473 2.43472 5.92612 0.990119 8.55251 0.58705C12.5931 -0.0320655 16.4317 2.86359 17.0915 6.95555C17.3061 8.29697 17.1009 9.55455 16.5864 10.7863C16.0119 12.16 15.27 13.4369 14.4619 14.6719C13.0193 16.8711 11.4062 18.9316 9.68894 20.9114C9.66999 20.934 9.6479 20.9598 9.61002 21.005ZM13.4076 7.58757C13.4107 5.45291 11.7061 3.71487 9.61317 3.71487C7.51711 3.71487 5.81563 5.44968 5.81563 7.58434C5.81563 9.719 7.51711 11.457 9.61002 11.457C11.7029 11.457 13.4044 9.72545 13.4076 7.58757Z"
                                            fill="#C8B0DB" />
                                        <path
                                            d="M6.65738 18.377C6.2912 18.4318 5.92818 18.4834 5.56831 18.5479C4.4824 18.7381 3.41858 19.0058 2.41474 19.4862C2.02331 19.6765 1.65081 19.8957 1.34461 20.215C0.852161 20.7309 0.852161 21.2597 1.35724 21.766C1.78655 22.1981 2.32004 22.4496 2.87247 22.6689C3.9426 23.0881 5.06008 23.317 6.19334 23.4718C7.75592 23.6878 9.32797 23.7394 10.9032 23.662C12.6678 23.575 14.4135 23.3493 16.096 22.7624C16.5948 22.5883 17.0809 22.3819 17.5102 22.0594C17.7028 21.9143 17.889 21.7466 18.0374 21.5564C18.3246 21.1888 18.3341 20.7793 18.0374 20.4214C17.8353 20.1763 17.586 19.9538 17.3208 19.7797C16.579 19.2928 15.7456 19.0154 14.8933 18.8155C14.1862 18.6511 13.4696 18.5447 12.7562 18.4125C12.693 18.3996 12.6331 18.3899 12.5699 18.3802C12.6867 18.1287 12.7088 18.1222 12.9645 18.1416C14.0189 18.2254 15.0701 18.3415 16.1023 18.5769C16.6642 18.7027 17.2198 18.8542 17.728 19.1283C18.0468 19.2992 18.3625 19.4991 18.6245 19.7442C19.2969 20.3762 19.4169 21.2533 18.9875 22.0788C18.7571 22.5238 18.4193 22.8752 18.0342 23.1816C17.1945 23.8491 16.238 24.2908 15.2342 24.6294C13.1224 25.342 10.9474 25.5774 8.73135 25.4807C7.02987 25.4033 5.36628 25.1228 3.75635 24.5359C2.76514 24.1747 1.82128 23.7233 1.01315 23.0139C0.659601 22.7043 0.359712 22.3432 0.170308 21.9014C-0.148521 21.1566 -0.00331158 20.3698 0.555429 19.7926C1.02262 19.3089 1.61293 19.0412 2.22849 18.8316C3.07134 18.5447 3.9426 18.3963 4.82017 18.2867C5.3063 18.2254 5.79244 18.19 6.28173 18.1384C6.51848 18.1158 6.53742 18.1287 6.65738 18.377Z"
                                            fill="#C8B0DB" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_4578_1300">
                                            <rect width="19.2308" height="25" fill="white"
                                                transform="translate(0 0.5)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <div class="contact-desc">
                                <p class="text-white fw-5 mb-0">New York, USA</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="gallery-section pt-60">
                <div class="section-heading center-heading text-center mx-30">
                    <h2 class="">Gallery.</h2>
                </div>
                <div class="gallery-slider ps-sm-4 px-3 pe-sm-0 ">
                    <div class="px-2">
                        <div class="gallery-img">
                            <img src="{{ asset('assets/img/vcard35/gallery-img1.png') }}" alt="gallery" />
                        </div>
                    </div>
                    <div class="px-2">
                        <div class="gallery-img">
                            <img src="{{ asset('assets/img/vcard35/gallery-img2.png') }}" alt="gallery" />
                        </div>
                    </div>
                    <div class="px-2">
                        <div class="gallery-img">
                            <img src="{{ asset('assets/img/vcard35/gallery-img3.png') }}" alt="gallery" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="our-services-section pt-60 pb-60 px-30">
                <div class="section-heading left-heading">
                    <h2 class="">Our Services.</h2>
                </div>
                <div class="services">
                    <div class="services-bg-img text-end">
                        <img src="{{ asset('assets/img/vcard35/services-bg.png') }}" alt="bg-vector" />
                    </div>
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="service-card card d-flex flex-row align-items-center">
                                <div class="card-img me-4">
                                    <img src="{{ asset('assets/img/vcard35/service-img1.png') }}" alt="service" />
                                </div>
                                <div class="card-body p-0">
                                    <h3 class="card-title fs-18 fw-6 text-white">
                                        Lorem Ipsum
                                    </h3>
                                    <p class="mb-0 fs-14 text-gray-300">
                                        Lorem Ipsum is dummy text of the printing & typesetting
                                        industry. Lorem Ipsum has been the industry's standard.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="service-card card d-flex flex-row align-items-center">
                                <div class="card-img me-4">
                                    <img src="{{ asset('assets/img/vcard35/service-img2.png') }}" alt="service" />
                                </div>
                                <div class="card-body p-0">
                                    <h3 class="card-title fs-18 fw-6 text-white">
                                        Lorem Ipsum
                                    </h3>
                                    <p class="mb-0 fs-14 text-gray-300">
                                        Lorem Ipsum is dummy text of the printing & typesetting
                                        industry. Lorem Ipsum has been the industry's standard.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="appointment-section position-relative px-30 pt-40 pb-40">
                <div class="overlay"></div>
                <div class="section-heading center-heading text-center">
                    <h2>Make An Appointment.</h2>
                </div>
                <div class="appointment">
                    <form>
                        <div class="row">
                            <div class="col-12 mb-20">
                                <label class="text-white fw-5 mb-2">Date:</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control appointment-input"
                                        placeholder="Pick a Date" />
                                    <span class="calendar-icon">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_102_145)">
                                                <path
                                                    d="M14.25 1.5H13.5V0.75C13.5 0.551088 13.421 0.360322 13.2803 0.21967C13.1397 0.0790176 12.9489 0 12.75 0C12.5511 0 12.3603 0.0790176 12.2197 0.21967C12.079 0.360322 12 0.551088 12 0.75V1.5H6V0.75C6 0.551088 5.92098 0.360322 5.78033 0.21967C5.63968 0.0790176 5.44891 0 5.25 0C5.05109 0 4.86032 0.0790176 4.71967 0.21967C4.57902 0.360322 4.5 0.551088 4.5 0.75V1.5H3.75C2.7558 1.50119 1.80267 1.89666 1.09966 2.59966C0.396661 3.30267 0.00119089 4.2558 0 5.25L0 14.25C0.00119089 15.2442 0.396661 16.1973 1.09966 16.9003C1.80267 17.6033 2.7558 17.9988 3.75 18H14.25C15.2442 17.9988 16.1973 17.6033 16.9003 16.9003C17.6033 16.1973 17.9988 15.2442 18 14.25V5.25C17.9988 4.2558 17.6033 3.30267 16.9003 2.59966C16.1973 1.89666 15.2442 1.50119 14.25 1.5ZM1.5 5.25C1.5 4.65326 1.73705 4.08097 2.15901 3.65901C2.58097 3.23705 3.15326 3 3.75 3H14.25C14.8467 3 15.419 3.23705 15.841 3.65901C16.2629 4.08097 16.5 4.65326 16.5 5.25V6H1.5V5.25ZM14.25 16.5H3.75C3.15326 16.5 2.58097 16.2629 2.15901 15.841C1.73705 15.419 1.5 14.8467 1.5 14.25V7.5H16.5V14.25C16.5 14.8467 16.2629 15.419 15.841 15.841C15.419 16.2629 14.8467 16.5 14.25 16.5Z"
                                                    fill="
                          #C8B0DB" />
                                                <path
                                                    d="M9 12.375C9.62132 12.375 10.125 11.8713 10.125 11.25C10.125 10.6287 9.62132 10.125 9 10.125C8.37868 10.125 7.875 10.6287 7.875 11.25C7.875 11.8713 8.37868 12.375 9 12.375Z"
                                                    fill="
                          #C8B0DB" />
                                                <path
                                                    d="M5.25 12.375C5.87132 12.375 6.375 11.8713 6.375 11.25C6.375 10.6287 5.87132 10.125 5.25 10.125C4.62868 10.125 4.125 10.6287 4.125 11.25C4.125 11.8713 4.62868 12.375 5.25 12.375Z"
                                                    fill="
                          #C8B0DB" />
                                                <path
                                                    d="M12.75 12.375C13.3713 12.375 13.875 11.8713 13.875 11.25C13.875 10.6287 13.3713 10.125 12.75 10.125C12.1287 10.125 11.625 10.6287 11.625 11.25C11.625 11.8713 12.1287 12.375 12.75 12.375Z"
                                                    fill="
                          #C8B0DB" />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_102_145">
                                                    <rect width="18" height="18" fill="#C8B0DB" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="text-white mb-2">Hour:</label>
                                <div class="row">
                                    <div class="col-sm-3 col-6 mb-20 pe-2">
                                        <div class="hour-input d-flex justify-content-center align-items-center">
                                            <span class="text-white">8:10 - 20:00</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-6 mb-20 px-2">
                                        <div class="hour-input d-flex justify-content-center align-items-center">
                                            <span class="text-white">8:10 - 20:00</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-6 mb-20 px-2">
                                        <div class="hour-input d-flex justify-content-center align-items-center">
                                            <span class="text-white">8:10 - 20:00</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-6 mb-20 ps-2">
                                        <div class="hour-input d-flex justify-content-center align-items-center">
                                            <span class="text-white">8:10 - 20:00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-center mt-2">
                                <button class="btn btn-primary">Make An Appointment</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="product-section position-relative pt-60">
                <div class="product-bg-img text-center">
                    <img src="{{ asset('assets/img/vcard35/product-bg.png') }}" alt="bg-vector" />
                </div>
                <div class="section-heading left-heading mx-30">
                    <h2 class="">Products.</h2>
                </div>
                <div class="product-slider">
                    <div>
                        <div class="product-card px-3">
                            <div class="card-img">
                                <img src="{{ asset('assets/img/vcard35/product-img1.png') }}" alt="product" />
                            </div>
                            <div class="card-body px-0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="text-white fw-5 mb-0">Modern style house</h6>
                                    <span class="fw-6 fs-16 text-white">$1,35,000</span>
                                </div>
                                <p class="text-gray-300 fs-14 mb-0">Lorem Ipsum is simply dummy text </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="product-card px-3">
                            <div class="card-img">
                                <img src="{{ asset('assets/img/vcard35/product-img2.png') }}" alt="product" />
                            </div>
                            <div class="card-body px-0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="text-white fw-5 mb-0">House with a pool</h6>
                                    <span class="fw-6 fs-16 text-white">$1,20,000</span>
                                </div>
                                <p class="text-gray-300 fs-14 mb-0">Lorem Ipsum is simply dummy text </p>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
            <div class="testimonial-section pt-60 px-30 position-relative">
                <div class="section-heading center-heading text-center">
                    <h2 class="">Testimonial.</h2>
                </div>
                <div class="position-relative">
                    <div class="testimonial-slider">
                        <div>
                            <div class="testimonial-card">
                                <div class="quote-img mb-4">
                                    <img src="{{ asset('assets/img/vcard35/quote-img.png') }}" alt="quote"
                                        class="w-100" />
                                </div>
                                <div class="card-body p-0">
                                    <p class="text-white fw-5 mb-4">
                                        Lorem Ipsum has been the industry's standard dummy text
                                        ever since the 1500s, when an unknown printer took a
                                        galley of type and scrambled it.
                                    </p>
                                    <div class="d-flex gap-3 align-items-center">
                                        <div class="card-img">
                                            <img src="{{ asset('assets/img/vcard35/testimonial-img.png') }}"
                                                alt="testimonial" class="w-100 h-100 object-fit-cover" />
                                        </div>
                                        <div>
                                            <h6 class="card-title fs-18 fw-5 mb-0 text-white">
                                                Kyle Woznick
                                            </h6>
                                            <p class="mb-0 fs-14 text-gray-300"><i>Customer</i></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="testimonial-card">
                                <div class="quote-img mb-4">
                                    <img src="{{ asset('assets/img/vcard35/quote-img.png') }}" alt="quote"
                                        class="w-100" />
                                </div>
                                <div class="card-body p-0">
                                    <p class="text-white fw-5 mb-4">
                                        Lorem Ipsum has been the industry's standard dummy text
                                        ever since the 1500s, when an unknown printer took a
                                        galley of type and scrambled it.
                                    </p>
                                    <div class="d-flex gap-3 align-items-center">
                                        <div class="card-img">
                                            <img src="{{ asset('assets/img/vcard35/testimonial-img.png') }}"
                                                alt="testimonial" class="w-100 h-100 object-fit-cover" />
                                        </div>
                                        <div>
                                            <h6 class="card-title fs-18 fw-5 mb-0 text-white">
                                                Kyle Woznick
                                            </h6>
                                            <p class="mb-0 fs-14 text-gray-300"><i>Customer</i></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-bg-img text-end">
                        <img src="{{ asset('assets/img/vcard35/testimonial-bg-img.png') }}" />
                    </div>
                </div>
            </div>
            <div class="business-hour-section pt-60">
                <div class="section-heading left-heading mx-30">
                    <h2 class="">Business Hours.</h2>
                </div>
                <div class="d-sm-flex justify-content-between align-items-center position-relative">
                    <div class="px-30">
                        <div class="bussiness-hour-card">
                            <div class="mb-10 d-flex align-items-center justify-content-between">
                                <span class="me-2">Sunday:</span>
                                <span>08:10 - 20:00</span>
                            </div>
                            <div class="mb-10 d-flex align-items-center justify-content-between">
                                <span class="me-2">Monday:</span>
                                <span>08:10 - 20:00</span>
                            </div>
                            <div class="mb-10 d-flex align-items-center justify-content-between">
                                <span class="me-2">Tueday:</span>
                                <span>08:10 - 20:00</span>
                            </div>
                            <div class="mb-10 d-flex align-items-center justify-content-between">
                                <span class="me-2">Wednesday:</span>
                                <span>08:10 - 20:00</span>
                            </div>
                            <div class="mb-10 d-flex align-items-center justify-content-between">
                                <span class="me-2">Thursday:</span>
                                <span>08:10 - 20:00</span>
                            </div>
                            <div class="mb-10 d-flex align-items-center justify-content-between">
                                <span class="me-2">Friday:</span>
                                <span>08:10 - 20:00</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="me-2">Saturday:</span>
                                <span>Closed</span>
                            </div>
                        </div>
                    </div>
                    <div class="business-hour-bg">
                        <img src="{{ asset('assets/img/vcard35/hour-bg-img.png') }}" alt="img"
                            class="w-100" />
                    </div>
                </div>
            </div>
            <div class="blog-section pt-60 px-30">
                <div class="section-heading center-heading text-center">
                    <h2 class="mb-0">Blog.</h2>
                </div>
                <div class="blog-slider">
                    <div>
                        <div
                            class="blog-card card px-sm-0 px-2 flex-sm-row flex-column justify-content-between align-items-center gap-4 mb-4">
                            <div class="blog-img card-img">
                                <img src="{{ asset('assets/img/vcard35/banner.png') }}"
                                    class="w-100 h-100 object-fit-cover" />
                            </div>
                            <div class="blog-desc text-sm-start text-center card-body p-0">
                                <div>
                                    <h3 class="text-white fs-18 fw-5 mb-2">
                                        Lorem Ipsum
                                    </h3>
                                    <p class="fs-14 text-gray-300 mb-0">Lorem Ipsum is simply dummy text of the
                                        printing
                                        and typesetting industry. Lorem Ipsum has been the industry's standard dummy
                                        text ever.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div
                            class="blog-card card px-sm-0 px-2 flex-sm-row flex-column-reverse justify-content-between align-items-center gap-4 mb-4">

                            <div class="blog-desc text-sm-start text-center card-body p-0">
                                <div>
                                    <h3 class="text-white fs-18 fw-5 mb-2">
                                        Lorem Ipsum
                                    </h3>
                                    <p class="fs-14 text-gray-300 mb-0">Lorem Ipsum is simply dummy text of the
                                        printing
                                        and typesetting industry. Lorem Ipsum has been the industry's standard dummy
                                        text ever.</p>
                                </div>
                            </div>
                            <div class="blog-img card-img">
                                <img src="{{ asset('assets/img/vcard35/banner.png') }}"
                                    class="w-100 h-100 object-fit-cover" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="qr-code-section pt-60">
                <div class="section-heading left-heading px-30">
                    <h2 class="">QR Code.</h2>
                </div>
                <div class="px-30">
                    <div class="d-flex flex-wrap gap-5 align-items-center px-30">
                        <div class="qr-vector-img">
                            <img src="{{ asset('assets/img/vcard35/qr-vector-img.png') }}"
                                class="w-100 h-100 object-fit-cover" />
                        </div>
                        <div class="qr-code d-flex justify-content-center position-relative ms-auto me-sm-4">
                            <div class="qr-code-img">
                                <img src="{{ asset('assets/img/vcard35/qr-code.png') }}"
                                    class="w-100 h-100 object-fit-cover" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="contact-us-section pt-60 px-30">
                <div class="section-heading center-heading text-center">
                    <h2 class="">Contact Us.</h2>
                </div>

                <div class="contact-form position-relative">
                    <form action="">
                        <div class="row">
                            <div class="col-sm-6 pe-sm-2">
                                <div class="mb-3">
                                    <input type="text" class="form-control" placeholder="Full Name" />
                                </div>
                            </div>
                            <div class="col-sm-6 ps-sm-2">
                                <div class="mb-3">
                                    <input type="email" class="form-control" placeholder="Phone Number" />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <input type="tel" class="form-control" placeholder="Email Address" />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <textarea class="form-control h-100" placeholder="Your Message" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-12 text-center mt-4">
                                <button class="btn btn-primary w-100" type="submit">
                                    Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="create-vcard-section pt-60 px-30">
                <div class="section-heading left-heading">
                    <h2 class="">Create Your Vcard.</h2>
                </div>
                <div class="vcard-link-card card">
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="https://vcards.infyom.com/marlonbrasil"
                            class="fw-5 fs-18 text-white link-text">https://vcards.infyom.com/marlonbrasil</a>
                        <i class="icon fa-solid fa-arrow-up-right-from-square ms-3 text-primary cursor-pointer"></i>
                    </div>
                </div>
            </div>
            <div class="add-to-contact-section pt-60">
                <div class="bg-img">
                    <img src="{{ asset('assets/img/vcard35/bg-img.png') }}" alt="bg-img" />
                </div>
                <div class="add-to-contact-btn pb-60 text-center">
                    <button class="btn btn-primary w-100">Add to Contact</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript" src="{{ asset('assets/js/front-third-party.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/slider/js/slick.min.js') }}" type="text/javascript"></script>
<script>
    $().ready(function() {
        $(".gallery-slider").slick({
            slidesToShow: 2,
            slidesToScroll: 1,
            arrows: false,
            dots: false,
            speed: 300,
            infinite: true,
            autoplaySpeed: 5000,
            autoplay: false,
            centerMode: true,
            responsive: [{
                    breakpoint: 575,
                    settings: {
                        dots: true,
                    },
                },
                {
                    breakpoint: 390,
                    settings: {
                        slidesToShow: 1,
                        dots: true,
                    },
                },
            ],
        });
        $(".product-slider").slick({
            arrows: false,
            infinite: true,
            dots: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            centerMode: true,
            centerPadding: "102px",
            responsive: [{
                    breakpoint: 575,
                    settings: {
                        dots: true,
                        centerPadding: "90px",
                    },
                },
                {
                    breakpoint: 496,
                    settings: {
                        centerPadding: "80px",
                        dots: true,
                    },
                },
                {
                    breakpoint: 460,
                    settings: {
                        centerPadding: "40px",
                        dots: true,
                    },
                },
                {
                    breakpoint: 390,
                    settings: {
                        centerPadding: "0",
                        dots: true,
                    },
                }
            ],
        });
        $(".testimonial-slider").slick({
            arrows: true,
            infinite: true,
            dots: false,
            slidesToShow: 1,
            autoplay: true,
            prevArrow: '<button class="slide-arrow prev-arrow"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="14" viewBox="0 0 28 14" fill="none"><path d="M26.8855 5.8333H3.79475L7.47246 1.99813C7.68232 1.77862 7.80022 1.48091 7.80022 1.17048C7.80022 0.860046 7.68232 0.562331 7.47246 0.342824C7.26261 0.123318 6.97798 0 6.6812 0C6.38442 0 6.09979 0.123318 5.88993 0.342824L0.317636 6.17135C0.216175 6.28222 0.136642 6.41294 0.0835995 6.55604C-0.0278665 6.83984 -0.0278665 7.15817 0.0835995 7.44197C0.136642 7.58507 0.216175 7.71579 0.317636 7.82666L5.88993 13.6552C5.99354 13.7644 6.1168 13.8512 6.2526 13.9103C6.38841 13.9695 6.53408 14 6.6812 14C6.82832 14 6.97399 13.9695 7.10979 13.9103C7.2456 13.8512 7.36886 13.7644 7.47246 13.6552C7.57692 13.5468 7.65983 13.4179 7.71641 13.2758C7.77299 13.1338 7.80212 12.9814 7.80212 12.8275C7.80212 12.6736 7.77299 12.5213 7.71641 12.3792C7.65983 12.2372 7.57692 12.1083 7.47246 11.9999L3.79475 8.16471H26.8855C27.1811 8.16471 27.4646 8.0419 27.6736 7.82328C27.8826 7.60467 28 7.30817 28 6.99901C28 6.68984 27.8826 6.39334 27.6736 6.17473C27.4646 5.95612 27.1811 5.8333 26.8855 5.8333Z" fill="#C8B0DB"/></svg></button>',
            nextArrow: '<button class="slide-arrow next-arrow right-rounded-0"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="14" viewBox="0 0 28 14" fill="none"><path d="M1.11446 5.8333H24.2053L20.5275 1.99813C20.3177 1.77862 20.1998 1.48091 20.1998 1.17048C20.1998 0.860046 20.3177 0.562331 20.5275 0.342824C20.7374 0.123318 21.022 0 21.3188 0C21.6156 0 21.9002 0.123318 22.1101 0.342824L27.6824 6.17135C27.7838 6.28222 27.8634 6.41294 27.9164 6.55604C28.0279 6.83984 28.0279 7.15817 27.9164 7.44197C27.8634 7.58507 27.7838 7.71579 27.6824 7.82666L22.1101 13.6552C22.0065 13.7644 21.8832 13.8512 21.7474 13.9103C21.6116 13.9695 21.4659 14 21.3188 14C21.1717 14 21.026 13.9695 20.8902 13.9103C20.7544 13.8512 20.6311 13.7644 20.5275 13.6552C20.4231 13.5468 20.3402 13.4179 20.2836 13.2758C20.227 13.1338 20.1979 12.9814 20.1979 12.8275C20.1979 12.6736 20.227 12.5213 20.2836 12.3792C20.3402 12.2372 20.4231 12.1083 20.5275 11.9999L24.2053 8.16471H1.11446C0.818884 8.16471 0.535414 8.0419 0.326412 7.82328C0.117411 7.60467 0 7.30817 0 6.99901C0 6.68984 0.117411 6.39334 0.326412 6.17473C0.535414 5.95612 0.818884 5.8333 1.11446 5.8333Z" fill="#C8B0DB"/></svg></button>',
            responsive: [{
                breakpoint: 575,
                settings: {
                    arrows: false,
                    dots: true,
                },
            }, ],
        });
        $(".blog-slider").slick({
            arrows: false,
            infinite: true,
            dots: false,
            slidesToShow: 2,
            slidesToScroll: 1,
            autoplay: true,
            vertical: true,
            responsive: [{
                breakpoint: 575,
                settings: {
                    centerPadding: "0",
                    vertical: false,
                    slidesToShow: 1,
                    dots: true,
                },
            }, ],
        });
    });
</script>

</html>
