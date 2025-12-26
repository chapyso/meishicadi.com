<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Photographer Vcard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap CSS -->
    <link href="{{ asset('front/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ getFaviconUrl() }}" type="image/png">

    {{-- css link --}}
    <link rel="stylesheet" href="{{ asset('assets/css/vcard34.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slider/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slider/css/slick-theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/new_vcard/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/new_vcard/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/new_vcard/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/third-party.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-vcard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lightbox.css') }}">
</head>

<body>
    <div class="container p-0">
        <div class="main-section mx-auto w-100 overflow-hidden position-relative">
            <div class="main-mask overflow-hidden">
                <div class="h-100 w-100 mb-60px">
                    <img src="{{ asset('assets/img/vcard34/hero.png') }}" alt="images"
                        class="h-100 object-fit-cover w-100" />
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
            <!-- Profile Section -->
            <div class="profile-section px-30px mt-2 position-relative">
                <div class="rotate-circle">
                    <div
                        class="circle rounded-circle d-flex justify-content-center align-items-center mx-auto mx-sm-0 position-relative">
                        <div class="profile-img position-relative rounded-circle">
                            <img src="{{ asset('assets/img/vcard34/profile-img.png') }}" alt="images"
                                class="h-100 w-100 object-fit-cover rounded-circle" />
                        </div>
                        <div class="rotate-text position-absolute">
                            <img src="{{ asset('assets/img/vcard34/black-text.png') }}" alt="images"
                                class="h-100 w-100 object-fit-cover" />
                        </div>
                    </div>
                </div>
                <div class="profile-desc">
                    <h1 class="fs-30 fw-semibold lh-base text-primary mb-2px text-center text-sm-start">
                        Michael Vice
                    </h1>
                    <p
                        class="fs-18 fw-light lh-base text-primary mb-0 photo-title text-uppercase text-center text-sm-start">
                        Photographer
                    </p>
                </div>
                <p class="description fs-14 text-primary mb-4 text-center text-sm-start mt-4">
                    Lorem Ipsum is simply dummy text of the printing and typesetting
                    industry. Lorem Ipsum has been the industry's standard dummy text ever
                    since the 1500s, when an unknown printer took a galley of type and
                    scrambled it to make a type specimen book.
                </p>
                <div
                class="social d-flex justify-content-sm-start justify-content-center align-items-center gap-3 flex-wrap mt-4 mb-4 mx-auto mx-sm-0 w-100">
                    <div class="text-decoration-none social-item d-flex align-items-center bg-white rounded-pill px-2 py-2 w-100">
                        <div class="me-5">
                            <a href="#"
                                class="text-decoration-none scoial-icon bg-white-100 rounded-circle d-flex justify-content-center align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="18" viewBox="0 0 9 18"
                                    fill="none">
                                    <path
                                        d="M2.39193 10.1048C2.01169 10.1048 1.66258 10.1048 1.31346 10.1048C1.0157 10.1048 0.717944 10.1104 0.420702 10.1025C0.109455 10.0947 0.00778118 9.99395 0.00518745 9.66091C-0.00155624 8.78829 -0.00207498 7.9151 0.0057062 7.04248C0.00881867 6.71224 0.117755 6.60757 0.424852 6.60533C1.00066 6.60141 1.57647 6.60421 2.15279 6.60421C2.22334 6.60421 2.29389 6.60421 2.39193 6.60421C2.39193 6.50961 2.3909 6.42845 2.39193 6.34785C2.40542 5.55247 2.37741 4.75373 2.4407 3.96283C2.60099 1.94835 4.02598 0.50983 5.89969 0.444342C6.66225 0.417474 7.42688 0.432587 8.18995 0.434826C8.45866 0.435386 8.5676 0.554049 8.56864 0.845111C8.57227 1.66176 8.57227 2.47897 8.56915 3.29562C8.56812 3.60851 8.46644 3.72214 8.16817 3.72942C7.64423 3.74285 7.1203 3.74285 6.59585 3.75181C6.00811 3.76132 5.76119 4.00928 5.74874 4.63563C5.73629 5.26868 5.74614 5.90286 5.74614 6.56055C5.83485 6.56055 5.90384 6.56055 5.97283 6.56055C6.63942 6.56055 7.30601 6.55943 7.9726 6.56111C8.39278 6.56167 8.49134 6.6669 8.49186 7.11245C8.49238 7.94309 8.49342 8.77429 8.49134 9.60494C8.49031 9.9766 8.39953 10.0768 8.053 10.0785C7.29615 10.0818 6.5393 10.0796 5.74666 10.0796C5.74666 10.177 5.74666 10.2637 5.74666 10.3499C5.74666 12.5351 5.74666 14.7209 5.74666 16.9061C5.74666 17.5084 5.68752 17.5711 5.12002 17.5711C4.37562 17.5711 3.63174 17.5722 2.88734 17.5705C2.48894 17.5699 2.39193 17.4664 2.39193 17.041C2.39142 14.8205 2.39142 12.6001 2.39142 10.3802C2.39193 10.2979 2.39193 10.2156 2.39193 10.1048Z"
                                        fill="#060606" />
                                </svg>
                            </a>
                        </div>
                        <div class="" style="margin-left: 120px;">
                            <span class="text-primary">Facebook</span>
                        </div>
                    </div>
                    <div class="text-decoration-none social-item d-flex align-items-center bg-white rounded-pill px-2 py-2 w-100">
                        <div class="me-5">
                            <a href="#"
                                class="text-decoration-none scoial-icon bg-white-100 rounded-circle d-flex justify-content-center align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                    fill="none">
                                    <path
                                        d="M17.5716 8.12615C17.5716 8.46127 17.5716 8.79596 17.5716 9.13108C17.5447 9.35169 17.5228 9.57272 17.4905 9.7925C17.2288 11.57 16.4751 13.108 15.2131 14.3889C13.8604 15.7621 12.2208 16.5715 10.3052 16.7934C8.63024 16.9876 7.03556 16.694 5.53666 15.9135C5.43331 15.8598 5.34551 15.8388 5.22495 15.882C4.3377 16.1999 3.44626 16.5057 2.55692 16.8182C1.84695 17.0673 1.13825 17.3202 0.428711 17.5714C0.443414 17.5014 0.450136 17.4288 0.473661 17.3617C0.998781 15.8577 1.52306 14.3528 2.05658 12.8517C2.11918 12.676 2.11456 12.5397 2.02718 12.3694C1.22185 10.8025 0.956772 9.13863 1.21513 7.40182C1.47475 5.6562 2.22882 4.13959 3.47819 2.88889C5.33123 1.03423 7.58336 0.232721 10.1896 0.468853C11.7738 0.612293 13.1984 1.19696 14.4419 2.19308C16.0613 3.48991 17.0742 5.15584 17.4413 7.2026C17.4959 7.50835 17.5287 7.8183 17.5716 8.12615ZM7.48464 7.19463C7.49514 7.17617 7.49683 7.1703 7.50061 7.16653C7.71696 6.94843 7.93457 6.73201 8.14923 6.51265C8.40129 6.25471 8.40129 5.97748 8.14671 5.72038C7.78123 5.35171 7.41407 4.9843 7.04438 4.61941C6.78392 4.3623 6.5096 4.3623 6.24998 4.61773C5.97944 4.88406 5.71898 5.16087 5.44172 5.42007C5.1279 5.71367 5.03464 6.07311 5.07707 6.4833C5.14807 7.16527 5.42953 7.77133 5.77737 8.34635C6.68184 9.84115 7.88163 11.0562 9.33811 12.0175C9.92708 12.4063 10.5526 12.7251 11.2542 12.8668C11.7936 12.9759 12.2691 12.8983 12.6522 12.4554C12.8707 12.2029 13.124 11.9802 13.3601 11.7424C13.6306 11.4693 13.6302 11.1992 13.3576 10.9245C13.0114 10.576 12.6627 10.2295 12.3149 9.88267C11.9956 9.56433 11.7436 9.5635 11.426 9.88016C11.2176 10.0882 11.0101 10.2971 10.8042 10.5034C9.30912 9.77153 8.21057 8.67433 7.48464 7.19463Z"
                                        fill="#060606" />
                                </svg>
                            </a>
                        </div>
                        <div class="" style="margin-left: 120px;">
                            <span class="text-primary">Whatsapp</span>
                        </div>
                    </div>
                    <div class="text-decoration-none social-item d-flex align-items-center bg-white rounded-pill px-2 py-2 w-100">
                        <div class="me-5">
                            <a href="#"
                                class="text-decoration-none scoial-icon bg-white-100 rounded-circle d-flex justify-content-center align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                    fill="none">
                                    <path
                                        d="M17.5608 10.6651C17.5552 9.86946 17.4759 9.07833 17.2445 8.30956C16.9551 7.34677 16.4304 6.57241 15.4945 6.13072C14.7715 5.78966 14.0005 5.69405 13.2126 5.71754C11.8336 5.75891 10.7178 6.29118 9.97191 7.49438C9.96353 7.5078 9.94118 7.51283 9.89424 7.53967C9.89424 7.01131 9.89424 6.50644 9.89424 6.001C8.74714 6.001 7.62797 6.001 6.5116 6.001C6.5116 9.8197 6.5116 13.6233 6.5116 17.4286C7.69335 17.4286 8.8561 17.4286 10.049 17.4286C10.049 17.324 10.049 17.2413 10.049 17.158C10.049 15.3722 10.044 13.5858 10.0529 11.8001C10.0546 11.4037 10.0803 11.0045 10.1367 10.6125C10.291 9.53791 10.8374 8.957 11.8297 8.83959C12.9198 8.71099 13.6809 9.10852 13.8965 10.2787C13.9731 10.6947 14.0066 11.1235 14.0094 11.5468C14.0217 13.4164 14.0161 15.2861 14.0167 17.1557C14.0167 17.2363 14.0167 17.3162 14.0167 17.3928C15.218 17.3928 16.3857 17.3928 17.5641 17.3928C17.5675 17.3458 17.5725 17.3123 17.5725 17.2782C17.5697 15.0742 17.5753 12.8696 17.5608 10.6651Z"
                                        fill="#060606" />
                                    <path
                                        d="M0.724357 17.4208C1.90834 17.4208 3.08114 17.4208 4.25786 17.4208C4.25786 13.6071 4.25786 9.80907 4.25786 5.9982C3.07835 5.9982 1.91057 5.9982 0.724357 5.9982C0.724357 9.81634 0.724357 13.6149 0.724357 17.4208Z"
                                        fill="#060606" />
                                    <path
                                        d="M2.47358 0.285802C1.34994 0.295866 0.429129 1.22398 0.429688 2.34555C0.430246 3.48613 1.37173 4.4299 2.50207 4.42319C3.62627 4.41648 4.5482 3.47439 4.54149 2.33772C4.53535 1.19715 3.60671 0.275739 2.47358 0.285802Z"
                                        fill="#060606" />
                                </svg>
                            </a>
                        </div>
                        <div class="" style="margin-left: 120px;">
                            <span class="text-primary">Linkedin</span>
                        </div>
                    </div>
                    <div class="text-decoration-none social-item d-flex align-items-center bg-white rounded-pill px-2 py-2 w-100">
                        <div class="me-5">
                            <a href="#"
                                class="text-decoration-none scoial-icon bg-white-100 rounded-circle d-flex justify-content-center align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                    fill="none">
                                    <path
                                        d="M17.5687 4.37914C17.5687 7.45919 17.5687 10.5387 17.5687 13.6188C17.501 13.939 17.4612 14.2686 17.3611 14.5784C16.7773 16.3797 15.1573 17.5615 13.2565 17.5667C10.417 17.5746 7.57747 17.5709 4.73797 17.5678C3.82606 17.5667 2.98543 17.3146 2.24699 16.7748C1.02692 15.8828 0.42841 14.6696 0.42841 13.1597C0.427886 10.3983 0.426314 7.63686 0.431031 4.87545C0.431555 4.59244 0.444657 4.30419 0.495493 4.02643C0.783217 2.44264 1.68307 1.33786 3.16938 0.733068C3.55039 0.577938 3.97438 0.527102 4.37845 0.428574C7.4585 0.428574 10.538 0.428574 13.6181 0.428574C13.7381 0.450061 13.8586 0.469976 13.9781 0.492512C15.5514 0.789669 16.6531 1.68062 17.2589 3.15592C17.4177 3.54112 17.4686 3.97035 17.5687 4.37914ZM16.13 8.99896C16.1321 8.99896 16.1342 8.99896 16.1363 8.99896C16.1363 7.60436 16.1337 6.20977 16.1379 4.81465C16.1389 4.36761 16.0792 3.93366 15.8795 3.53169C15.3387 2.44211 14.4566 1.86614 13.2371 1.86195C10.4086 1.85199 7.58009 1.85828 4.7516 1.86038C4.58494 1.86038 4.41723 1.8761 4.25267 1.89968C2.95451 2.08259 1.87961 3.25654 1.87122 4.57095C1.85183 7.48854 1.86336 10.4061 1.86179 13.3237C1.86179 13.7199 1.93935 14.1025 2.11387 14.4568C2.66207 15.5673 3.56087 16.1344 4.80139 16.136C7.59634 16.1402 10.3913 16.1381 13.1862 16.1354C13.3749 16.1354 13.5652 16.1208 13.7523 16.0951C15.0253 15.9206 16.108 14.7335 16.1253 13.45C16.1458 11.9669 16.13 10.4832 16.13 8.99896Z"
                                        fill="#060606" />
                                    <path
                                        d="M13.2819 9.01992C13.2594 11.393 11.3155 13.3138 8.96762 13.2828C6.59718 13.2519 4.69055 11.3228 4.71309 8.97695C4.73562 6.60284 6.67789 4.68416 9.02684 4.71403C11.3978 4.74495 13.3044 6.67411 13.2819 9.01992ZM9.00011 6.14583C7.42366 6.14321 6.15118 7.4115 6.14489 8.99215C6.13808 10.5665 7.40532 11.8416 8.98701 11.8521C10.5661 11.862 11.8522 10.5801 11.8506 8.99739C11.849 7.41884 10.5787 6.14845 9.00011 6.14583Z"
                                        fill="#060606" />
                                    <path
                                        d="M13.6486 5.42208C13.0522 5.42627 12.5742 4.95197 12.5742 4.35608C12.5747 3.76963 13.0438 3.29533 13.6323 3.2859C14.2136 3.27647 14.7125 3.7712 14.712 4.35661C14.712 4.93992 14.2345 5.41788 13.6486 5.42208Z"
                                        fill="#060606" />
                                </svg>
                             </a>
                         </div>
                         <div class="" style="margin-left: 120px;">
                            <span class="text-primary">Instagram</span>
                         </div>
                    </div>
                    <div class="text-decoration-none social-item d-flex align-items-center bg-white rounded-pill px-2 py-2 w-100">
                        <div class="me-5">
                            <a href="#"
                                class="text-decoration-none scoial-icon bg-white-100 rounded-circle d-flex justify-content-center align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="15" viewBox="0 0 17 15"
                                    fill="none">
                                    <g clip-path="url(#clip0_4389_671)">
                                        <path
                                            d="M5.38447 11.1975C3.85585 10.9886 2.84166 10.2305 2.26843 8.71428C2.76082 8.71428 3.19442 8.71428 3.62067 8.71428C2.07 8.0722 1.19545 6.95049 1.08522 5.17122C1.55556 5.31047 1.98916 5.44198 2.42276 5.56575C2.44481 5.53481 2.46686 5.50386 2.4889 5.47292C1.84218 4.92367 1.37918 4.2429 1.2175 3.37648C1.05582 2.51005 1.12931 1.67457 1.57761 0.815882C3.42959 3.0361 5.69313 4.328 8.47846 4.51366C8.47846 4.18875 8.47111 3.91026 8.47846 3.63177C8.52255 2.1542 9.21337 1.0789 10.4701 0.483236C11.6974 -0.0969598 12.9026 0.057759 13.9609 0.970601C14.2549 1.22589 14.5195 1.28004 14.8502 1.14079C15.3573 0.931921 15.8644 0.72305 16.4155 0.506444C16.1804 1.28004 15.6953 1.80608 15.1882 2.37854C15.7174 2.23156 16.2465 2.07684 16.7756 1.92986C16.7977 1.95307 16.8271 1.97627 16.8491 1.99948C16.4302 2.4559 16.0334 2.951 15.5777 3.36101C15.3279 3.58535 15.2323 3.80969 15.225 4.14234C15.1809 6.47086 14.5195 8.58277 13.1525 10.4239C11.3152 12.9072 8.87531 14.1604 5.86951 14.2223C4.14981 14.261 2.54035 13.8819 1.02642 13.0232C0.87944 12.9381 0.732457 12.8453 0.578125 12.7138C2.31987 12.7989 3.90729 12.3656 5.38447 11.1975Z"
                                            fill="#060606" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_4389_671">
                                            <rect width="16.2857" height="14.0794" fill="white"
                                                transform="translate(0.572266 0.142853)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </a>
                        </div>
                        <div class="" style="margin-left: 120px;">
                            <span class="text-primary">Twitter</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Personal Section -->
            <div class="personal-section position-relative px-30px overflow-hidden">
                <div class="lens-img rounded-circle mx-auto mx-sm-0 mt-40px mb-40px">
                    <img src="{{ asset('assets/img/vcard34/lens.png') }}" alt="images"
                        class="h-100 w-100 object-fit-cover rounded-circle" />
                </div>
                <div class="personal-detalis position-relative">
                    <div class="detalis-id d-flex flex-column gap-15 mb-40px w-100 me-auto ms-sm-auto me-sm-0">
                        <div class="d-flex gap-15 text-decoration-none align-items-center">
                            <div
                                class="detalis-icon bg-primary rounded-circle d-flex justify-content-center align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="13"
                                    viewBox="0 0 18 13" fill="none">
                                    <path
                                        d="M9.01754 0.400994C11.2584 0.400994 13.4948 0.396994 15.7357 0.404993C16.0287 0.404993 16.3263 0.43299 16.6104 0.488984C17.1199 0.592973 17.4716 0.880942 17.6925 1.2969C17.8864 1.66086 17.8232 1.86084 17.4445 2.07281C16.953 2.34878 16.4571 2.62075 15.9656 2.89672C13.8194 4.10859 11.6732 5.31246 9.53606 6.53233C9.1483 6.75631 8.8417 6.74831 8.45394 6.52833C5.85236 5.04449 3.23726 3.58065 0.626658 2.10881C0.572552 2.07681 0.513938 2.04882 0.464341 2.01682C0.207339 1.86084 0.157742 1.73685 0.247918 1.47688C0.46885 0.836947 1.01892 0.464987 1.826 0.424991C2.15063 0.408993 2.47527 0.408993 2.79539 0.404993C4.86944 0.404993 6.94349 0.404993 9.01754 0.400994Z"
                                        fill="#F3F1ED" />
                                    <path
                                        d="M8.98044 12.396C6.7035 12.396 4.43106 12.4 2.15411 12.392C1.87006 12.392 1.57698 12.364 1.30195 12.296C0.747364 12.156 0.404695 11.8081 0.237869 11.3241C0.152202 11.0721 0.201799 10.9481 0.4588 10.8042C2.5599 9.63229 4.66101 8.46042 6.76211 7.28854C6.98304 7.16456 7.2175 7.16456 7.43843 7.28054C7.83521 7.48852 8.22296 7.7045 8.61072 7.92447C8.93085 8.10845 9.06611 8.11245 9.37722 7.93247C9.78752 7.6965 10.2023 7.46852 10.6126 7.23255C10.8336 7.10456 11.0319 7.14456 11.2348 7.26055C11.9067 7.6445 12.5785 8.02446 13.2548 8.40442C14.6345 9.18034 16.0187 9.94826 17.3984 10.7242C17.8222 10.9601 17.8898 11.1561 17.6824 11.5521C17.3984 12.088 16.8844 12.316 16.2577 12.384C16.0998 12.4 15.942 12.4 15.7842 12.4C13.5163 12.396 11.2484 12.396 8.98044 12.396Z"
                                        fill="#F3F1ED" />
                                    <path
                                        d="M0.23359 9.61629C0.23359 7.46453 0.23359 5.34876 0.23359 3.19699C2.13631 4.27287 4.01197 5.32876 5.91919 6.40464C4.01648 7.48052 2.14081 8.54041 0.23359 9.61629Z"
                                        fill="#F3F1ED" />
                                    <path
                                        d="M12.096 6.40464C13.9942 5.33276 15.8699 4.27287 17.7726 3.19699C17.7726 5.34076 17.7726 7.45253 17.7726 9.6083C15.8744 8.54041 13.9987 7.48053 12.096 6.40464Z"
                                        fill="#F3F1ED" />
                                </svg>
                            </div>
                            <a href="mailto:michael@gmail.com"
                                class="fs-16 fw-medium lh-base text-primary mb-0 text-break">
                                michael@gmail.com
                            </a>
                        </div>
                        <div class="d-flex gap-15 text-decoration-none align-items-center">
                            <div
                                class="detalis-icon bg-primary rounded-circle d-flex justify-content-center align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 16 16" fill="none">
                                    <path
                                        d="M3.2303 6.92509C4.54459 9.4459 6.41125 11.3579 8.87792 12.6706C8.98268 12.7277 9.2303 12.6611 9.33506 12.566C9.89697 12.0333 10.4494 11.4911 10.9827 10.9298C11.2779 10.6159 11.6017 10.5684 12.0113 10.6445C12.9827 10.8157 13.9636 10.9869 14.9446 11.1011C15.7255 11.1962 16.0017 11.453 16.0017 12.2521C16.0017 13.1082 16.0017 13.9548 16.0017 14.8109C16.0017 15.7336 15.7065 16.019 14.7636 16C8.05887 15.8858 2.05887 11.1296 0.477921 4.60404C0.20173 3.45303 0.116016 2.24495 0.0112543 1.05589C-0.0554124 0.351962 0.335064 0.00951249 1.03983 0.00951249C1.97316 0 2.90649 0 3.83983 0C4.52554 0 4.81125 0.313912 4.89697 0.998811C5.0303 2.00713 5.21125 3.01546 5.40173 4.01427C5.47792 4.44233 5.40173 4.78478 5.08745 5.08918C4.45887 5.69798 3.84935 6.30678 3.2303 6.92509Z"
                                        fill="#F3F1ED" />
                                </svg>
                            </div>
                            <a href="tel:+1 4078461474" class="fs-16 fw-medium lh-base text-primary mb-0">
                                +1 4078461474
                            </a>
                        </div>
                        <div class="d-flex gap-15 text-decoration-none align-items-center">
                            <div
                                class="detalis-icon bg-primary rounded-circle d-flex justify-content-center align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="16"
                                    viewBox="0 0 20 16" fill="none">
                                    <g clip-path="url(#clip0_4330_5418)">
                                        <path
                                            d="M0.650877 16C0.570239 15.8532 0.441217 15.7141 0.417026 15.5596C0.376707 15.2815 0.417026 14.9956 0.400898 14.7175C0.392834 14.4626 0.505728 14.3312 0.779899 14.3389C0.860537 14.3389 0.941175 14.3389 1.02181 14.3389C7.01325 14.3389 12.9966 14.3389 18.988 14.3467C19.1896 14.3467 19.3912 14.4239 19.5928 14.4626C19.5928 14.9725 19.5928 15.4901 19.5928 16C13.2869 16 6.96486 16 0.650877 16Z"
                                            fill="#F3F1ED" />
                                        <path
                                            d="M10.0207 6.55143C12.1738 6.55143 14.3268 6.55143 16.4879 6.55143C17.7056 6.55143 18.3023 7.13086 18.3104 8.30517C18.3104 8.32062 18.3104 8.32835 18.3104 8.3438C18.4555 9.02366 18.1249 9.40995 17.4959 9.67263C16.8589 9.9353 16.246 9.98938 15.6896 9.53356C15.4154 9.30952 15.1896 9.03912 14.9639 8.76871C14.6655 8.42106 14.3833 8.41333 14.0768 8.75326C13.8752 8.97731 13.6736 9.20136 13.4479 9.3945C12.8108 9.94303 12.1093 10.0048 11.3916 9.56447C11.069 9.3636 10.7787 9.10865 10.4965 8.8537C10.1094 8.50604 9.94009 8.50604 9.56109 8.86915C9.34336 9.07774 9.11757 9.27861 8.8676 9.44858C8.02089 10.0357 7.25483 9.99711 6.46457 9.34815C6.34361 9.24771 6.23072 9.13955 6.12589 9.02366C5.58561 8.42878 5.47272 8.42878 4.93244 9.04684C4.19863 9.88122 3.27129 10.0666 2.23912 9.61855C1.83593 9.44085 1.68272 9.17045 1.72303 8.76871C1.74723 8.57557 1.77142 8.38243 1.74723 8.19701C1.61014 7.24674 2.40846 6.53598 3.44063 6.5437C5.62593 6.56688 7.8193 6.55143 10.0207 6.55143Z"
                                            fill="#F3F1ED" />
                                        <path
                                            d="M18.1493 13.296C12.7223 13.296 7.31954 13.296 1.89258 13.296C1.89258 12.4848 1.89258 11.6736 1.89258 10.8315C3.23924 11.2641 4.41656 10.986 5.38422 9.98939C6.99699 11.4727 8.48073 11.2719 10.0371 9.94304C11.9643 11.55 13.3513 11.1792 14.6657 9.91986C15.1092 10.3757 15.593 10.7929 16.2543 10.9551C16.9074 11.1174 17.5203 10.986 18.1654 10.7233C18.1493 11.5886 18.1493 12.423 18.1493 13.296Z"
                                            fill="#F3F1ED" />
                                        <path
                                            d="M8.86725 5.82522C8.86725 5.06038 8.85919 4.31098 8.87531 3.55386C8.88338 3.30664 9.06885 3.15985 9.33495 3.15985C9.77846 3.15212 10.222 3.15212 10.6574 3.15985C10.9638 3.15985 11.1413 3.32981 11.1413 3.61566C11.1574 4.34188 11.1493 5.07583 11.1493 5.82522C10.3833 5.82522 9.64138 5.82522 8.86725 5.82522Z"
                                            fill="#F3F1ED" />
                                        <path
                                            d="M10.0116 0C10.2052 0.339932 10.431 0.726219 10.6487 1.12023C10.7455 1.29792 10.8745 1.47562 10.9309 1.66876C11.0519 2.09367 10.8583 2.55722 10.5116 2.74264C10.1649 2.92805 9.63264 2.87397 9.35041 2.62675C9.05205 2.35635 8.95528 1.83873 9.16494 1.46016C9.43911 0.950265 9.73747 0.455818 10.0116 0Z"
                                            fill="#F3F1ED" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_4330_5418">
                                            <rect width="19.2" height="16" fill="white"
                                                transform="translate(0.400391)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <p class="fs-16 fw-medium lh-base text-primary mb-0">
                                12th June, 1990
                            </p>
                        </div>
                        <div class="d-flex gap-15 text-decoration-none align-items-center">
                            <div
                                class="detalis-icon bg-primary rounded-circle d-flex justify-content-center align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="20"
                                    viewBox="0 0 16 20" fill="none">
                                    <g clip-path="url(#clip0_4330_5429)">
                                        <path
                                            d="M7.99577 16.1478C7.75467 15.8581 7.52302 15.5832 7.2961 15.3058C6.07168 13.7952 4.91343 12.2325 3.9112 10.551C3.45972 9.79569 3.06498 9.01065 2.72696 8.19094C2.10529 6.67782 2.21166 5.18947 2.89715 3.74817C3.78356 1.88587 5.23727 0.776417 7.20392 0.46686C10.2295 -0.00862098 13.1039 2.21524 13.5979 5.35787C13.7586 6.38808 13.605 7.3539 13.2197 8.29991C12.7895 9.35488 12.234 10.3356 11.6289 11.284C10.5486 12.973 9.34075 14.5554 8.05487 16.076C8.04069 16.0933 8.02414 16.1131 7.99577 16.1478ZM10.8394 5.84326C10.8417 4.20384 9.56531 2.86903 7.99814 2.86903C6.4286 2.86903 5.15454 4.20136 5.15454 5.84078C5.15454 7.4802 6.4286 8.81501 7.99577 8.81501C9.56295 8.81501 10.837 7.48515 10.8394 5.84326Z"
                                            fill="#F3F1ED" />
                                        <path
                                            d="M5.78681 14.1295C5.51261 14.1716 5.24078 14.2113 4.97131 14.2608C4.15818 14.4069 3.36159 14.6124 2.60992 14.9814C2.31681 15.1275 2.03789 15.2959 1.80861 15.5411C1.43986 15.9373 1.43986 16.3435 1.81806 16.7323C2.13953 17.0641 2.53901 17.2573 2.95266 17.4257C3.75398 17.7476 4.59075 17.9235 5.43934 18.0423C6.6094 18.2083 7.78654 18.2479 8.96606 18.1885C10.2874 18.1216 11.5946 17.9482 12.8544 17.4975C13.2279 17.3638 13.5919 17.2053 13.9134 16.9576C14.0576 16.8462 14.1971 16.7174 14.3081 16.5713C14.5232 16.289 14.5303 15.9745 14.3081 15.6996C14.1569 15.5114 13.9701 15.3405 13.7716 15.2068C13.2161 14.8328 12.5921 14.6199 11.9538 14.4663C11.4244 14.34 10.8878 14.2583 10.3536 14.1568C10.3063 14.1469 10.2614 14.1394 10.2141 14.132C10.3016 13.9388 10.3181 13.9339 10.5096 13.9487C11.2991 14.0131 12.0862 14.1023 12.8592 14.2831C13.2799 14.3797 13.6959 14.496 14.0765 14.7065C14.3152 14.8378 14.5516 14.9913 14.7478 15.1796C15.2513 15.6649 15.3411 16.3385 15.0196 16.9725C14.8471 17.3143 14.5942 17.5842 14.3058 17.8195C13.677 18.3321 12.9608 18.6714 12.2091 18.9314C10.6278 19.4787 8.99915 19.6595 7.3398 19.5852C6.06573 19.5257 4.82003 19.3103 3.61452 18.8596C2.8723 18.5822 2.16553 18.2355 1.56041 17.6907C1.29567 17.4529 1.07111 17.1756 0.929289 16.8363C0.69055 16.2642 0.799283 15.66 1.21767 15.2167C1.5675 14.8452 2.00953 14.6397 2.47046 14.4787C3.10158 14.2583 3.75398 14.1444 4.4111 14.0602C4.77512 14.0131 5.13914 13.9859 5.50552 13.9463C5.6828 13.9289 5.69698 13.9388 5.78681 14.1295Z"
                                            fill="#F3F1ED" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_4330_5429">
                                            <rect width="14.4" height="19.2" fill="white"
                                                transform="translate(0.800781 0.400024)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <p class="fs-16 fw-medium lh-base text-primary mb-0">
                                New York, USA
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Gallery Section -->
            <div class="gallery-section">
                <div>
                    <p class="section-heading fs-24 lh-base fw-semibold mx-auto mb-40px">
                        Gallery
                    </p>
                    <div class="gallery-slider position-relative">
                        <div>
                            <div class="gallery-img mx-auto w-100">
                                <img src="{{ asset('assets/img/vcard34/gallery2.png') }}" alt="images"
                                    class="h-100 w-100 object-fit-cover" />
                            </div>
                        </div>
                        <div>
                            <div class="gallery-img mx-auto w-100">
                                <img src="{{ asset('assets/img/vcard34/gallery1.png') }}" alt="images"
                                    class="h-100 w-100 object-fit-cover" />
                            </div>
                        </div>
                        <div>
                            <div class="gallery-img mx-auto w-100">
                                <img src="{{ asset('assets/img/vcard34/gallery3.png') }}" alt="images"
                                    class="h-100 w-100 object-fit-cover" />
                            </div>
                        </div>
                        <div>
                            <div class="gallery-img mx-auto w-100">
                                <img src="{{ asset('assets/img/vcard34/gallery2.png') }}" alt="images"
                                    class="h-100 w-100 object-fit-cover" />
                            </div>
                        </div>
                        <div>
                            <div class="gallery-img mx-auto w-100">
                                <img src="{{ asset('assets/img/vcard34/gallery1.png') }}" alt="images"
                                    class="h-100 w-100 object-fit-cover" />
                            </div>
                        </div>
                        <div>
                            <div class="gallery-img mx-auto w-100">
                                <img src="{{ asset('assets/img/vcard34/gallery3.png') }}" alt="images"
                                    class="h-100 w-100 object-fit-cover" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Our Services Section -->
            <div class="our-section px-30px mb-60px">
                <p class="section-heading fs-24 lh-base fw-semibold mx-auto mb-40px">
                    Our Services
                </p>
                <div class="row services-card">
                    <div class="col-sm-6">
                        <div class="our-card1">
                            <div class="our-img1 mb-20px">
                                <img src="{{ asset('assets/img/vcard34/wedding.png') }}" alt="images"
                                    class="h-100 w-100 object-fit-contain" />
                            </div>
                            <h2 class="fs-18 fw-semibold text-primary lh-base mb-10px">
                                Wedding Photoshoot
                            </h2>
                            <p class="fs-14 lh-sm text-gray-100 mb-0">
                                Lorem Ipsum is simply dummy text of the printing & typesetting
                                industry. Lorem Ipsum has been the industry's standard dummy.
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="our-card1">
                            <div class="our-img1 mb-20px">
                                <img src="{{ asset('assets/img/vcard34/fashion.png') }}" alt="images"
                                    class="h-100 w-100 object-fit-contain" />
                            </div>
                            <h2 class="fs-18 fw-semibold text-primary lh-base mb-10px">
                                Fashion Photoshoot
                            </h2>
                            <p class="fs-14 lh-sm text-gray-100 mb-0">
                                Lorem Ipsum is simply dummy text of the printing & typesetting
                                industry. Lorem Ipsum has been the industry's standard dummy.
                            </p>
                        </div>
                    </div>


                </div>
            </div>
            <!-- Make an Appointment -->
            <div class="appointment-section px-30px overflow-hidden pb-40px">
                <div class="make-title d-flex mb-40px">
                    <h2 class="text-white-100 fs-24 lh-base mb-0 d-inline">
                        Make an Appointment
                    </h2>
                </div>
                <div class="book-select">
                    <div class="input-group mb-15px bg-primary date" id="month-input">
                        <input type="text"
                            class="form-control fs-16 lh-base border-0 p-0 rounded-0 bg-primary text-white-100"
                            placeholder="Pick a date" aria-label="Pick a date" aria-describedby="basic-addon2" />
                        <div class="input-group-append mx-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                viewBox="0 0 18 18" fill="none">
                                <path
                                    d="M5.625 8.4375V9.5625C5.625 9.87342 5.37342 10.125 5.0625 10.125H3.9375C3.62658 10.125 3.375 9.87342 3.375 9.5625V8.4375C3.375 8.12658 3.62658 7.875 3.9375 7.875H5.0625C5.37342 7.875 5.625 8.12658 5.625 8.4375ZM5.0625 12.375H3.9375C3.62658 12.375 3.375 12.6266 3.375 12.9375V14.0625C3.375 14.3734 3.62658 14.625 3.9375 14.625H5.0625C5.37342 14.625 5.625 14.3734 5.625 14.0625V12.9375C5.625 12.6266 5.37342 12.375 5.0625 12.375ZM9.5625 7.875H8.4375C8.12658 7.875 7.875 8.12658 7.875 8.4375V9.5625C7.875 9.87342 8.12658 10.125 8.4375 10.125H9.5625C9.87342 10.125 10.125 9.87342 10.125 9.5625V8.4375C10.125 8.12658 9.87342 7.875 9.5625 7.875ZM9.5625 12.375H8.4375C8.12658 12.375 7.875 12.6266 7.875 12.9375V14.0625C7.875 14.3734 8.12658 14.625 8.4375 14.625H9.5625C9.87342 14.625 10.125 14.3734 10.125 14.0625V12.9375C10.125 12.6266 9.87342 12.375 9.5625 12.375ZM14.0625 7.875H12.9375C12.6266 7.875 12.375 8.12658 12.375 8.4375V9.5625C12.375 9.87342 12.6266 10.125 12.9375 10.125H14.0625C14.3734 10.125 14.625 9.87342 14.625 9.5625V8.4375C14.625 8.12658 14.3734 7.875 14.0625 7.875ZM14.0625 12.375H12.9375C12.6266 12.375 12.375 12.6266 12.375 12.9375V14.0625C12.375 14.3734 12.6266 14.625 12.9375 14.625H14.0625C14.3734 14.625 14.625 14.3734 14.625 14.0625V12.9375C14.625 12.6266 14.3734 12.375 14.0625 12.375ZM3.9375 3.375H5.0625C5.37342 3.375 5.625 3.12342 5.625 2.8125V0.5625C5.625 0.251578 5.37342 0 5.0625 0H3.9375C3.62658 0 3.375 0.251578 3.375 0.5625V2.8125C3.375 3.12342 3.62658 3.375 3.9375 3.375ZM18 4.5V15.75C18 16.9926 16.9926 18 15.75 18H2.25C1.00744 18 0 16.9926 0 15.75V4.5C0 3.25744 1.00744 2.25 2.25 2.25H2.8125V2.8125C2.8125 3.43213 3.31731 3.9375 3.9375 3.9375H5.0625C5.68269 3.9375 6.1875 3.43213 6.1875 2.8125V2.25H11.8125V2.8125C11.8125 3.43213 12.3179 3.9375 12.9375 3.9375H14.0625C14.6821 3.9375 15.1875 3.43213 15.1875 2.8125V2.25H15.75C16.9926 2.25 18 3.25744 18 4.5ZM16.875 6.75C16.875 6.13037 16.3707 5.625 15.75 5.625H2.25C1.62981 5.625 1.125 6.13037 1.125 6.75V15.75C1.125 16.3707 1.62981 16.875 2.25 16.875H15.75C16.3707 16.875 16.875 16.3707 16.875 15.75V6.75ZM12.9375 3.375H14.0625C14.3734 3.375 14.625 3.12342 14.625 2.8125V0.5625C14.625 0.251578 14.3734 0 14.0625 0H12.9375C12.6266 0 12.375 0.251578 12.375 0.5625V2.8125C12.375 3.12342 12.6266 3.375 12.9375 3.375Z"
                                    fill="#F3F1ED" />
                            </svg>
                        </div>
                    </div>
                    <div class="hour-input d-flex gap-10 flex-wrap gap-10 mb-30px">
                        <div
                            class="time-section d-flex justify-content-center align-items-center text-white fs-16 lh-base">
                            8:10 - 20:00
                        </div>
                        <div
                            class="time-section d-flex justify-content-center align-items-center text-white fs-16 lh-base">
                            8:10 - 20:00
                        </div>
                        <div
                            class="time-section d-flex justify-content-center align-items-center text-white fs-16 lh-base">
                            8:10 - 20:00
                        </div>
                        <div
                            class="time-section d-flex justify-content-center align-items-center text-white fs-16 lh-base">
                            8:10 - 20:00
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-white text-primary fw-semibold fs-16 lh-base time-btn w-100">
                            Book Appointment
                        </button>
                    </div>
                </div>
            </div>
            <!-- Products Section -->
            <div class="products-section mt-60px mb-60px">
                <p class="section-heading fs-24 lh-base fw-semibold mx-auto mb-40px">
                    Products
                </p>
                <div class="product-slider">
                    <div>
                        <div class="product mx-auto">
                            <div class="product-img mb-15px">
                                <img src="{{ asset('assets/img/vcard34/product1.png') }}" alt="image"
                                    class="h-100 w-100 object-fit-cover" />
                            </div>
                            <div class="product-content">
                                <div class="d-flex justify-content-between gsp-15 mb-2">
                                    <h2 class="fs-16 lh-base text-primary mb-0">
                                        Digital Camera
                                    </h2>
                                    <p class="fs-16 lh-base text-primary fw-semibold mb-0">
                                        $25.00
                                    </p>
                                </div>
                                <p class="fs-14 lh-sm text-gray-100">Lorem Ipsum dummy text</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="product mx-auto">
                            <div class="product-img mb-15px">
                                <img src="{{ asset('assets/img/vcard34/product2.png') }}" alt="image"
                                    class="h-100 w-100 object-fit-cover" />
                            </div>
                            <div class="product-content">
                                <div class="d-flex justify-content-between gsp-15 mb-2">
                                    <h2 class="fs-16 lh-base text-primary mb-0">Camera Lens</h2>
                                    <p class="fs-16 lh-base text-primary fw-semibold mb-0">
                                        $25.00
                                    </p>
                                </div>
                                <p class="fs-14 lh-sm text-gray-100">Lorem Ipsum dummy text</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="product mx-auto">
                            <div class="product-img mb-15px">
                                <img src="{{ asset('assets/img/vcard34/product1.png') }}" alt="image"
                                    class="h-100 w-100 object-fit-cover" />
                            </div>
                            <div class="product-content">
                                <div class="d-flex justify-content-between gsp-15 mb-2">
                                    <h2 class="fs-16 lh-base text-primary mb-0">
                                        Digital Camera
                                    </h2>
                                    <p class="fs-16 lh-base text-primary fw-semibold mb-0">
                                        $25.00
                                    </p>
                                </div>
                                <p class="fs-14 lh-sm text-gray-100">Lorem Ipsum dummy text</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="product mx-auto">
                            <div class="product-img mb-15px">
                                <img src="{{ asset('assets/img/vcard34/product2.png') }}" alt="image"
                                    class="h-100 w-100 object-fit-cover" />
                            </div>
                            <div class="product-content">
                                <div class="d-flex justify-content-between gsp-15 mb-2">
                                    <h2 class="fs-16 lh-base text-primary mb-0">Camera Lens</h2>
                                    <p class="fs-16 lh-base text-primary fw-semibold mb-0">
                                        $25.00
                                    </p>
                                </div>
                                <p class="fs-14 lh-sm text-gray-100">Lorem Ipsum dummy text</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Testimonial Section -->
            <div class="testimonial-section px-30px position-relative overflow-hidden">
                <div class="test-left position-absolute top-0">
                    <img src="{{ asset('assets/img/vcard34/testleft.png') }}" alt="images" class="img-fluid" />
                </div>
                <p class="section-heading fs-24 lh-base fw-semibold mx-auto mb-40px">
                    Testimonial
                </p>
                <div class="d-flex justify-content-center arrow-custom position-relative z-3 start-0 end-0 mx-auto">
                    <button type="button" class="prev border-0 bg-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="12" viewBox="0 0 38 12"
                            fill="none">
                            <path
                                d="M37.0322 4.99997H3.29544L6.48925 1.71268C6.67149 1.52453 6.77387 1.26935 6.77387 1.00327C6.77387 0.737183 6.67149 0.481998 6.48925 0.29385C6.307 0.105701 6.05983 0 5.80209 0C5.54436 0 5.29719 0.105701 5.11494 0.29385L0.275842 5.28973C0.187731 5.38476 0.118662 5.49681 0.0725996 5.61946C-0.0241999 5.86272 -0.0241999 6.13557 0.0725996 6.37884C0.118662 6.50149 0.187731 6.61354 0.275842 6.70856L5.11494 11.7044C5.20491 11.7981 5.31195 11.8724 5.42989 11.9232C5.54783 11.9739 5.67433 12 5.80209 12C5.92986 12 6.05636 11.9739 6.17429 11.9232C6.29223 11.8724 6.39927 11.7981 6.48925 11.7044C6.57996 11.6116 6.65196 11.501 6.70109 11.3793C6.75023 11.2575 6.77553 11.1269 6.77553 10.995C6.77553 10.8631 6.75023 10.7325 6.70109 10.6108C6.65196 10.489 6.57996 10.3785 6.48925 10.2856L3.29544 6.99832H37.0322C37.2889 6.99832 37.535 6.89305 37.7165 6.70567C37.898 6.51829 38 6.26415 38 5.99915C38 5.73415 37.898 5.48001 37.7165 5.29262C37.535 5.10524 37.2889 4.99997 37.0322 4.99997Z"
                                fill="#060606" />
                        </svg>
                    </button>
                    <button type="button" class="next border-0 bg-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="12" viewBox="0 0 38 12"
                            fill="none">
                            <path
                                d="M0.967815 4.99997H34.7046L31.5108 1.71268C31.3285 1.52453 31.2261 1.26935 31.2261 1.00327C31.2261 0.737183 31.3285 0.481998 31.5108 0.29385C31.693 0.105701 31.9402 0 32.1979 0C32.4556 0 32.7028 0.105701 32.8851 0.29385L37.7242 5.28973C37.8123 5.38476 37.8813 5.49681 37.9274 5.61946C38.0242 5.86272 38.0242 6.13557 37.9274 6.37884C37.8813 6.50149 37.8123 6.61354 37.7242 6.70856L32.8851 11.7044C32.7951 11.7981 32.688 11.8724 32.5701 11.9232C32.4522 11.9739 32.3257 12 32.1979 12C32.0701 12 31.9436 11.9739 31.8257 11.9232C31.7078 11.8724 31.6007 11.7981 31.5108 11.7044C31.42 11.6116 31.348 11.501 31.2989 11.3793C31.2498 11.2575 31.2245 11.1269 31.2245 10.995C31.2245 10.8631 31.2498 10.7325 31.2989 10.6108C31.348 10.489 31.42 10.3785 31.5108 10.2856L34.7046 6.99832H0.967815C0.711132 6.99832 0.464966 6.89305 0.283463 6.70567C0.101959 6.51829 0 6.26415 0 5.99915C0 5.73415 0.101959 5.48001 0.283463 5.29262C0.464966 5.10524 0.711132 4.99997 0.967815 4.99997Z"
                                fill="#060606" />
                        </svg>
                    </button>
                </div>
                <div class="testimonial-slider">
                    <div>
                        <div class="test">
                            <div class="test-img rounded-circle mx-auto mb-30px">
                                <img src="{{ asset('assets/img/vcard34/test1.jpeg') }}" alt="images"
                                    class="h-100 w-100 object-fit-cover rounded-circle" />
                            </div>
                            <div class="test-content">
                                <p class="fs-14 lh-sm text-gray-100 mb-15px text-center">
                                    Lorem Ipsum is simply dummy text of the printing and
                                    typesetting industry. Lorem Ipsum has been the industry's
                                    standard dummy text ever since the 1500s, when an unknown
                                    printer took a galley of type and scrambled it to make a type
                                    specimen book.
                                </p>
                                <h2 class="fs-18 fw-semibold text-primary lh-base mb-0 text-center">
                                    Richard Madden
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="test">
                            <div class="test-img rounded-circle mx-auto mb-30px">
                                <img src="{{ asset('assets/img/vcard34/test1.jpeg') }}" alt="images"
                                    class="h-100 w-100 object-fit-cover rounded-circle" />
                            </div>
                            <div class="test-content">
                                <p class="fs-14 lh-sm text-gray-100 mb-15px text-center">
                                    Lorem Ipsum is simply dummy text of the printing and
                                    typesetting industry. Lorem Ipsum has been the industry's
                                    standard dummy text ever since the 1500s, when an unknown
                                    printer took a galley of type and scrambled it to make a type
                                    specimen book.
                                </p>
                                <h2 class="fs-18 fw-semibold text-primary lh-base mb-0 text-center">
                                    Richard Madden
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Blog Section -->
            <div class="blog-section position-relative mt-60px">
                <div class="test-right position-absolute end-0 text-end">
                    <img src="{{ asset('assets/img/vcard34/testright.png') }}" alt="images" class="img-fluid" />
                </div>
                <p class="section-heading fs-24 lh-base fw-semibold mx-auto mb-40px">
                    Blog
                </p>
                <div class="blog-slider position-relative">
                    <div>
                        <div class="blog-card mx-auto position-relative w-100">
                            <div class="blog-img w-100">
                                <img src="{{ asset('assets/img/vcard34/blog3.png') }}" alt="images"
                                    class="h-100 w-100 object-fit-cover" />
                            </div>
                            <div class="blog-test position-absolute z-2">
                                <p class="fs-18 fw-semibold text-white lh-base mb-10px blog-title">
                                    Lorem Ipsum
                                </p>
                                <p class="fs-14 text-white lh-sm mb-0 blog-content">
                                    It is a long established fact that a reader will be distracted
                                    by the readable content of a page when looking.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="blog-card mx-auto position-relative w-100">
                            <div class="blog-img w-100">
                                <img src="{{ asset('assets/img/vcard34/blog1.png') }}" alt="images"
                                    class="h-100 w-100 object-fit-cover" />
                            </div>
                            <div class="blog-test position-absolute z-2">
                                <p class="fs-18 fw-semibold text-white lh-base mb-10px blog-title">
                                    Lorem Ipsum
                                </p>
                                <p class="fs-14 text-white lh-sm mb-0 blog-content">
                                    It is a long established fact that a reader will be distracted
                                    by the readable content of a page when looking.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="blog-card mx-auto position-relative w-100">
                            <div class="blog-img w-100">
                                <img src="{{ asset('assets/img/vcard34/blog2.png') }}" alt="images"
                                    class="h-100 w-100 object-fit-cover" />
                            </div>
                            <div class="blog-test position-absolute z-2">
                                <p class="fs-18 fw-semibold text-white lh-base mb-10px blog-title">
                                    Lorem Ipsum
                                </p>
                                <p class="fs-14 text-white lh-sm mb-0 blog-content">
                                    It is a long established fact that a reader will be distracted
                                    by the readable content of a page when looking.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Business Hours Section -->
            <div class="business-hour position-relative mb-60px">
                <div class="businees-right position-absolute end-0 text-end bottom-0">
                    <img src="{{ asset('assets/img/vcard34/business.png') }}" alt="images" class="img-fluid" />
                </div>
                <div class="position-relative">
                    <div class="d-flex mb-40px">
                        <p
                            class="section-heading fs-24 lh-base fw-semibold mx-auto mb-0 business-title text-center w-100 position-relative">
                            Business Hours
                        </p>
                    </div>
                    <div class="time-table w-100">
                        <div class="d-flex justify-content-between gap-15 mb-15px time-detail">
                            <p class="fs-16 fw-medium lh-base text-primary mb-0 days">
                                Sunday :
                            </p>
                            <p class="fs-16 fw-medium lh-base text-primary mb-0">
                                08:10 - 20:00
                            </p>
                        </div>
                        <div class="d-flex justify-content-between gap-15 mb-15px time-detail">
                            <p class="fs-16 fw-medium lh-base text-primary mb-0 days">
                                Monday :
                            </p>
                            <p class="fs-16 fw-medium lh-base text-primary mb-0">
                                08:10 - 20:00
                            </p>
                        </div>
                        <div class="d-flex justify-content-between gap-15 mb-15px time-detail">
                            <p class="fs-16 fw-medium lh-base text-primary mb-0 days">
                                Tuesday :
                            </p>
                            <p class="fs-16 fw-medium lh-base text-primary mb-0">
                                08:10 - 20:00
                            </p>
                        </div>
                        <div class="d-flex justify-content-between gap-15 mb-15px time-detail">
                            <p class="fs-16 fw-medium lh-base text-primary mb-0 days">
                                Wednesday:
                            </p>
                            <p class="fs-16 fw-medium lh-base text-primary mb-0">
                                08:10 - 20:00
                            </p>
                        </div>
                        <div class="d-flex justify-content-between gap-15 mb-15px time-detail">
                            <p class="fs-16 fw-medium lh-base text-primary mb-0 days">
                                Thursday :
                            </p>
                            <p class="fs-16 fw-medium lh-base text-primary mb-0">
                                08:10 - 20:00
                            </p>
                        </div>
                        <div class="d-flex justify-content-between gap-15 mb-15px time-detail">
                            <p class="fs-16 fw-medium lh-base text-primary mb-0 days">
                                Friday :
                            </p>
                            <p class="fs-16 fw-medium lh-base text-primary mb-0">
                                08:10 - 20:00
                            </p>
                        </div>
                        <div class="d-flex justify-content-between gap-15 mb-15px time-detail">
                            <p class="fs-16 fw-medium lh-base text-primary mb-0 days">
                                Saturday :
                            </p>
                            <p class="fs-16 fw-medium lh-base text-primary mb-0">Closed</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Qr code Section -->
            <div class="qr-section">
                <div
                    class="qr-code d-flex justify-content-center align-items-center position-relative mb-40px pb-40px">
                    <h2
                        class="fs-24 fw-semibold text-white-100 lh-base mb-0 m-0 py-0 text-center z-3 d-inline position-absolute bottom-0">
                        QR Code
                    </h2>
                </div>
                <div class="container mb-40px">
                    <div class="row align-items-center m-0 flex-column flex-sm-row">
                        <div class="col-6 mb-sm-0 mb-4">
                            <div class="qr-logo d-flex justify-content-center align-items-center">
                                <div
                                    class="circle d-flex justify-content-center align-items-center rounded-circle position-relative">
                                    <div class="profile-img position-relative rounded-circle">
                                        <img src="{{ asset('assets/img/vcard34/profile-img.png') }}" alt="images"
                                            class="h-100 w-100 object-fit-cover rounded-circle" />
                                    </div>
                                    <div class="rotate-text position-absolute">
                                        <img src="{{ asset('assets/img/vcard34/white-text.png') }}" alt="images"
                                            class="h-100 w-100 object-fit-cover" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="qr-img d-flex justify-content-center align-items-center">
                                    <img src="{{ asset('assets/img/vcard34/qrcode.png') }}" alt="images"
                                        class="h-100 w-100" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Contact Us Section -->
            <div class="contact-section px-40px mt-60px mb-60px">
                <p class="section-heading fs-24 lh-base fw-semibold mx-auto mb-40px">
                    Inquiries
                </p>
                <form>
                    <div class="d-flex gap-26 mb-20px flex-wrap input-join">
                        <input type="text"
                            class="form-control fs-16 lh-base text-gray-100 rounded-0 border-top-0 border-start-0 border-end-0"
                            placeholder="Full Name" required />
                        <input type="text"
                            class="form-control fs-16 lh-base text-gray-100 rounded-0 border-top-0 border-start-0 border-end-0"
                            placeholder="Phone Number" required />
                    </div>
                    <div class="mb-20px">
                        <input type="email"
                            class="form-control fs-16 lh-base text-gray-100 rounded-0 border-top-0 border-start-0 border-end-0"
                            placeholder="Email Address" required />
                    </div>
                    <div class="mb-30px">
                        <textarea rows="3" placeholder="Your Message"
                            class="w-100 border-top-0 border-start-0 border-end-0 text-gray-100"></textarea>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-primary send-btn w-100" type="submit">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
            <!-- V-card Section-->
            <div class="v-card-section py-30px px-30px">
                <div class="v-bg-img position-relative mb-60px pb-40px">
                    <h3
                        class="text-center text-white-100 fs-24 lh-base fw-light text-uppercase position-relative z-1 start-0 end-0">
                        Create Your VCard
                    </h3>
                    <div class="d-flex justify-content-center align-items-center position-relative z-1">
                        <div
                            class="input-group v-card-input d-flex justify-content-center gap-20 align-items-center flex-nowrap position-relative z-2 gap-20">
                            <div class="text-white-100 fs-16 lh-base text-break fw-medium">
                                https://vcards.infyom.com/marlonbrasil
                            </div>
                            <div class="input-group-append mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 16 16" fill="none">
                                    <path
                                        d="M14.3965 7.95047H13.2665C13.0325 7.95047 12.8428 8.14019 12.8428 8.37421V13.5261C12.8428 13.7996 12.6203 14.0221 12.3468 14.0221H2.47324C2.19984 14.0221 1.97746 13.7996 1.97746 13.5261V3.65272C1.97746 3.37921 2.19984 3.15666 2.47324 3.15666H7.88893C8.12295 3.15666 8.31267 2.96693 8.31267 2.73292V1.60294C8.31267 1.36892 8.12295 1.1792 7.88893 1.1792H2.47324C1.10947 1.1792 0 2.28884 0 3.65272V13.5262C0 14.89 1.10952 15.9996 2.47324 15.9996H12.3467C13.7106 15.9996 14.8202 14.89 14.8202 13.5262V8.37427C14.8203 8.14019 14.6305 7.95047 14.3965 7.95047Z"
                                        fill="#F3F1ED" />
                                    <path
                                        d="M15.5764 0.000488281H11.0818C10.8477 0.000488281 10.658 0.190211 10.658 0.424229V1.55421C10.658 1.78822 10.8477 1.97795 11.0818 1.97795H12.6244L6.81943 7.7828C6.65394 7.94829 6.65394 8.21655 6.81943 8.38209L7.61843 9.18115C7.69793 9.26064 7.80567 9.30528 7.9181 9.30528C8.03048 9.30528 8.13828 9.26064 8.21772 9.18115L14.0227 3.37618V4.91877C14.0227 5.15278 14.2124 5.34251 14.4464 5.34251H15.5764C15.8104 5.34251 16.0001 5.15278 16.0001 4.91877V0.424229C16.0001 0.190211 15.8104 0.000488281 15.5764 0.000488281Z"
                                        fill="#F3F1ED" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="#"
                    class="btn btn-primary fw-semibold fs-16 lh-base add-btn position-fixed start-0 end-0 mx-auto"
                    type="submit">Add to Contact</a>
            </div>
        </div>
    </div>
</body>
@include('vcardTemplates.template.templates')
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript" src="{{ asset('assets/js/front-third-party.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/slider/js/slick.min.js') }}" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        $("#month-input").datepicker({
            dateFormat: "dd/mm/yy",
        });
    });
</script>
<script>
    $(".product-slider").slick({
        dots: false,
        infinite: true,
        arrows: false,
        speed: 300,
        slidesToShow: 2,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1000,
        responsive: [{
            breakpoint: 575,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
            },
        }, ],
    });
    $(".testimonial-slider").slick({
        slidesToShow: 1,
        infinite: true,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1000,
        arrows: true,
        prevArrow: $(".prev"),
        nextArrow: $(".next"),
        dots: false,
    });
    $(".blog-slider").slick({
        dots: false,
        centerMode: true,
        centerPadding: "78px",
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1000,
        arrows: false,
        responsive: [{
            breakpoint: 575,
            settings: {
                centerPadding: "0px",
                slidesToShow: 1,
                dots: true,
            },
        }, ],
    });
    $(".gallery-slider").slick({
        dots: true,
        centerMode: true,
        centerPadding: "133px",
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 1000,
        arrows: false,
        responsive: [{
            breakpoint: 575,
            settings: {
                centerPadding: "0",
            },
        }, ],
    });
</script>
<script>
    $("#myID").flatpickr();
</script>

<script>
    $(document).ready(function() {
        $('.dropdown1').hover(function() {
            $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(100);
        }, function() {
            $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(100);
        });
    });
</script>
</html>
