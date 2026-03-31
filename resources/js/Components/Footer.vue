<script setup>
</script>

<template>
    <footer class="bg-base-200 text-base-content border-t border-base-300">
        <!-- Footer Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="footer">
                <nav>
                    <h6 class="footer-title">Hizmetlerimiz</h6>
                    <a :href="route('product.show')" class="link link-hover">1C ERP Çözümü</a>
                    <a :href="route('product.show')" class="link link-hover">Kurulum ve Danışmanlık</a>
                    <a href="/#messaging" class="link link-hover">Teknik Destek</a>
                    <a href="/#messaging" class="link link-hover">IoT Entegrasyon</a>
                </nav>
                <nav>
                    <h6 class="footer-title">Sayfalar</h6>
                    <a :href="route('welcome')" class="link link-hover">Anasayfa</a>
                    <a :href="route('product.show')" class="link link-hover">ERP Çözümü</a>
                    <a :href="route('cart.index')" class="link link-hover">Sepet</a>
                    <a href="/#messaging" class="link link-hover">Bize Ulaşın</a>
                </nav>
                <nav>
                    <h6 class="footer-title">Yasal</h6>
                    <a :href="route('gizlilik')" class="link link-hover">Gizlilik Sözleşmesi</a>
                    <a :href="route('mesafeli')" class="link link-hover">Mesafeli Satış Sözleşmesi</a>
                    <a :href="route('iade')" class="link link-hover">İade Sözleşmesi</a>
                </nav>
                <nav>
                    <h6 class="footer-title">Iletisim</h6>
                    <a :href="'tel:' + $page.props.contact.phone" class="link link-hover flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        {{ $page.props.contact.phone.replace(/(\+90)(\d{3})(\d{3})(\d{2})(\d{2})/, '0$2 $3 $4 $5') }}
                    </a>
                    <a href="mailto:info@hayatbilisim.com" class="link link-hover flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        info@hayatbilisim.com
                    </a>
                    <span class="flex items-center gap-2 text-base-content/70 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-60 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Istanbul, Turkiye
                    </span>
                </nav>
                <nav>
                    <h6 class="footer-title">Sosyal Medya</h6>
                    <div class="grid grid-flow-col gap-4">
                        <!-- X (Twitter) Link -->
                        <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current">
                                <path
                                    d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z">
                                </path>
                            </svg>
                        </a>
                        <!-- YouTube Link -->
                        <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current">
                                <path
                                    d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z">
                                </path>
                            </svg>
                        </a>
                        <!-- Facebook Link -->
                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current">
                                <path
                                    d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z">
                                </path>
                            </svg>
                        </a>
                        <!-- LinkedIn Link -->
                        <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current">
                                <path
                                    d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z">
                                </path>
                            </svg>
                        </a>
                    </div>
                </nav>
            </div>
        </div>
        
        <!-- Footer Bottom Section with Payment Methods -->
        <div class="bg-base-300 border-t border-base-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <!-- Copyright -->
                    <div class="text-sm text-base-content/70">
                        <p>&copy; {{ new Date().getFullYear() }} {{ $page.props.app?.name || 'Hayat Bilişim' }}. Tüm hakları saklıdır.</p>
                    </div>
                    
                    <!-- Payment Methods -->
                    <div class="flex items-center gap-3 flex-wrap justify-center md:justify-end">
                        <span class="text-sm text-base-content/70">Güvenli Ödeme:</span>
                        <div class="flex items-center gap-2">
                            <!-- Visa -->
                            <div class="bg-white rounded px-2 py-1.5 shadow-sm hover:shadow-md transition-shadow border border-base-300 w-16" title="Visa">
                                <svg viewBox="0 0 780 500" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto"><path fill="#1434cb" d="M780 0H0v500h780z"/><path fill="#fff" d="M489.823 143.111c-46.835 0-88.689 24.282-88.689 69.145 0 51.45 74.23 55.003 74.23 80.85 0 10.883-12.469 20.625-33.764 20.625-30.223 0-52.811-13.612-52.811-13.612l-9.666 45.272s26.022 11.498 60.569 11.498c51.206 0 91.498-25.474 91.498-71.105 0-54.365-74.538-57.813-74.538-81.803 0-8.526 10.235-17.867 31.47-17.867 23.959 0 43.506 9.9 43.506 9.9l9.459-43.725s-21.269-9.178-51.264-9.178m-428.494 3.3-1.134 6.6s19.704 3.607 37.45 10.803c22.85 8.25 24.477 13.054 28.326 27.972l41.934 161.7h56.213l86.601-207.075h-56.084l-55.646 140.791-22.707-119.341c-2.083-13.658-12.631-21.45-25.542-21.45zm271.942 0-43.996 207.075h53.481l43.842-207.075zm298.283 0c-12.896 0-19.729 6.907-24.743 18.975l-78.353 188.1h56.084l10.851-31.35h68.327l6.598 31.35h49.487l-43.172-207.075zm7.294 55.945 16.625 77.705h-44.538z"/></svg>
                            </div>
                            
                            <!-- Mastercard -->
                            <div class="bg-white rounded px-2 py-1.5 shadow-sm hover:shadow-md transition-shadow border border-base-300 w-16" title="Mastercard">
                                <svg viewBox="0 0 780 500" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto"><path fill="#253747" d="M780 0H0v500h780z"/><path fill="#fff" d="M211.053 467.045v-28.936c0-11.068-6.742-18.316-18.317-18.316-5.787 0-12.079 1.91-16.406 8.203-3.371-5.281-8.203-8.203-15.451-8.203-4.832 0-9.664 1.461-13.484 6.742v-5.787h-10.114v46.297h10.114v-25.564c0-8.203 4.326-12.08 11.068-12.08s10.114 4.326 10.114 12.08v25.564h10.113v-25.564c0-8.203 4.832-12.08 11.069-12.08 6.742 0 10.113 4.326 10.113 12.08v25.564zm150.015-46.297h-16.406v-13.99h-10.113v13.99h-9.158v9.158h9.158v21.239c0 10.619 4.326 16.855 15.9 16.855 4.327 0 9.159-1.461 12.53-3.371l-2.922-8.709c-2.922 1.911-6.293 2.416-8.709 2.416-4.832 0-6.742-2.921-6.742-7.697v-20.733h16.406v-9.158zm85.852-1.011c-5.787 0-9.664 2.921-12.08 6.742v-5.787h-10.113v46.297h10.113v-26.07c0-7.698 3.371-12.08 9.664-12.08 1.91 0 4.326.506 6.293.955l2.921-9.664c-2.022-.393-4.888-.393-6.798-.393m-129.733 4.832c-4.831-3.371-11.574-4.832-18.822-4.832-11.574 0-19.271 5.787-19.271 14.945 0 7.698 5.787 12.08 15.9 13.485l4.832.505c5.282.956 8.203 2.416 8.203 4.832 0 3.371-3.877 5.787-10.619 5.787s-12.08-2.416-15.451-4.832l-4.832 7.698c5.282 3.877 12.53 5.787 19.777 5.787 13.485 0 21.239-6.293 21.239-14.945 0-8.203-6.293-12.53-15.901-13.991l-4.832-.505c-4.326-.506-7.697-1.461-7.697-4.327 0-3.371 3.371-5.281 8.708-5.281 5.788 0 11.575 2.416 14.496 3.877zm269.073-4.832c-5.787 0-9.664 2.921-12.08 6.742v-5.787h-10.113v46.297h10.113v-26.07c0-7.698 3.371-12.08 9.664-12.08 1.91 0 4.326.506 6.293.955l2.922-9.552c-1.967-.505-4.832-.505-6.799-.505m-129.227 24.16c0 13.99 9.664 24.103 24.61 24.103 6.742 0 11.574-1.461 16.406-5.281l-4.832-8.203c-3.877 2.921-7.698 4.326-12.08 4.326-8.203 0-13.99-5.787-13.99-14.945 0-8.709 5.787-14.496 13.99-14.946 4.326 0 8.203 1.461 12.08 4.327l4.832-8.204c-4.832-3.876-9.664-5.281-16.406-5.281-14.946-.056-24.61 10.113-24.61 24.104m93.549 0v-23.149h-10.113v5.787c-3.371-4.326-8.203-6.742-14.496-6.742-13.035 0-23.148 10.113-23.148 24.104S512.938 468 525.973 468c6.742 0 11.574-2.416 14.496-6.742v5.787h10.113zm-37.138 0c0-8.204 5.281-14.946 13.99-14.946 8.203 0 13.99 6.293 13.99 14.946 0 8.203-5.787 14.945-13.99 14.945-8.653-.506-13.99-6.799-13.99-14.945m-121.024-24.16c-13.485 0-23.149 9.664-23.149 24.103 0 14.496 9.664 24.104 23.655 24.104 6.742 0 13.484-1.91 18.822-6.293l-4.832-7.248c-3.877 2.922-8.709 4.832-13.485 4.832-6.292 0-12.529-2.921-13.99-11.068h34.217v-3.877c.45-14.889-8.259-24.553-21.238-24.553m0 8.709c6.293 0 10.619 3.876 11.574 11.068h-24.103c.955-6.236 5.281-11.068 12.529-11.068m251.262 15.451v-41.465h-10.114v24.103c-3.371-4.326-8.203-6.742-14.495-6.742-13.036 0-23.149 10.113-23.149 24.104S606.037 468 619.073 468c6.742 0 11.574-2.416 14.495-6.742v5.787h10.114zm-37.139 0c0-8.204 5.282-14.946 13.99-14.946 8.203 0 13.991 6.293 13.991 14.946 0 8.203-5.788 14.945-13.991 14.945-8.708-.506-13.99-6.799-13.99-14.945m-338.574 0v-23.149h-10.114v5.787c-3.371-4.326-8.203-6.742-14.495-6.742-13.035 0-23.149 10.113-23.149 24.104S230.325 468 243.36 468c6.742 0 11.574-2.416 14.495-6.742v5.787h10.114zm-37.588 0c0-8.204 5.281-14.946 13.99-14.946 8.203 0 13.99 6.293 13.99 14.946 0 8.203-5.787 14.945-13.99 14.945-8.709-.506-13.99-6.799-13.99-14.945"/><path fill="#ff5a00" d="M465.738 69.139H313.812v272.949h151.926z"/><path fill="#eb001b" d="M323.926 205.613c0-55.455 26.07-104.673 66.074-136.474C360.559 45.99 323.42 32 282.91 32c-95.965 0-173.613 77.648-173.613 173.613s77.648 173.614 173.613 173.614c40.51 0 77.649-13.99 107.09-37.139-40.06-31.351-66.074-81.019-66.074-136.475"/><path fill="#f79e1b" d="M670.711 205.613c0 95.965-77.649 173.614-173.613 173.614-40.51 0-77.649-13.99-107.09-37.139 40.51-31.857 66.074-81.019 66.074-136.475s-26.07-104.673-66.074-136.474C419.393 45.99 456.532 32 497.041 32c96.021 0 173.67 78.154 173.67 173.613"/></svg>
                            </div>
                            
                            <!-- Troy -->
                            <div class="bg-white rounded px-2 py-1.5 shadow-sm hover:shadow-md transition-shadow border border-base-300 w-16" title="Troy">
                                <svg viewBox="0 0 1989 916" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto" style="shape-rendering:geometricPrecision;text-rendering:geometricPrecision;image-rendering:optimizeQuality;fill-rule:evenodd;clip-rule:evenodd"><path fill="#4b565b" d="M129.5-.5h117q54.804 7.62 46.5 63a11070 11070 0 0 0-14.5 84h111a15305 15305 0 0 0-24 140h-112a17878 17878 0 0 0-32 185q1.812 35.063 35 46.5 32.243 9.944 66 7.5l-27 156q-79.298 8.27-155-16.5Q36.92 625.647 42 514.5a321 321 0 0 1 3-29 20756 20756 0 0 0 34.5-199h-80v-7q12.295-65.76 24-132 40.35-1.007 80.5-2 12.915-72.99 25.5-146" style="opacity:.995"/><path fill="#4b565b" d="M792.5 140.5a869 869 0 0 0 23 2 41171 41171 0 0 1-31 177q-78.435-14.369-143 31.5-51.76 44.735-64.5 112.5-19.264 107.311-38.5 214.5-89.499.75-179 .5a222263 222263 0 0 0 93-532q56.002-.25 112 .5 57.6 5.11 49.5 62.5a1010 1010 0 0 0-9 57q43.561-80.567 128.5-114.5 28.838-10.3 59-11.5" style="opacity:.993"/><path fill="#24b8c8" d="M1063.5 141.5h39a41228 41228 0 0 1-28.5 162c-42.29 7.962-71.95 31.629-89 71q-22.11 70.317 31.5 120.5a157.5 157.5 0 0 0 20 13c.58.893.91 1.893 1 3q-14.445 80.716-28.5 161.5-147.74-49.24-187-200-29.94-156.172 85.5-264.5 67.53-57.507 156-66.5" style="opacity:.993"/><path fill="#4b565b" d="M1988.5 146.5v3a261844 261844 0 0 0-324.5 608q-42.405 75.398-115.5 121.5c-35.12 20.358-72.79 32.524-113 36.5h-184c7.16-42.32 14.33-84.654 21.5-127 3.42-15.356 11.25-27.856 23.5-37.5a35.3 35.3 0 0 1 14-5l108-1c34.6-5.926 59.43-24.76 74.5-56.5q10.89-25.46 3-52-69.9-244.464-139.5-489a5163 5163 0 0 1 176-.5q37.065 5.57 43.5 42.5a33891 33891 0 0 0 47 294 56223 56223 0 0 0 127-291q18.3-39.301 61.5-45.5 88.5-.75 177-.5" style="opacity:.995"/><path fill="#24b8c8" d="M1163.5 152.5c79.4 24.57 136.57 74.57 171.5 150 25.04 59.497 29.38 120.497 13 183-24.8 81.133-75.63 139.633-152.5 175.5-40.17 17.696-81.84 24.53-125 20.5a38527 38527 0 0 0 28.5-160c35.48-6.826 62.81-25.492 82-56q33.585-68.518-15-127a115.7 115.7 0 0 0-29.5-22c-.33-.833-.67-1.667-1-2.5a9749 9749 0 0 0 28-161.5" style="opacity:.992"/><path fill="#4b565b" d="M1988.5 691.5v22q-18.405 57.961-78 43.5c-32.23-15.651-43.4-40.817-33.5-75.5q24.285-48.51 76.5-32.5c18.66 8.544 30.33 22.711 35 42.5m-66-37q43.68-2.81 55.5 39 4.545 43.922-37.5 56.5-41.82 4.161-55.5-35.5-6.6-46.1 37.5-60m-1 25q7.515-.249 15 .5c7.44 3.651 9.27 9.151 5.5 16.5a6.97 6.97 0 0 1-3.5 2.5 57 57 0 0 1-17 1.5z" style="opacity:.903"/><path fill="#4b565b" d="M1912.5 670.5c8.67-.166 17.34 0 26 .5 13.27 4.1 18.1 12.933 14.5 26.5-.67 1.333-1.33 2.667-2 4a89 89 0 0 1-9.5 7 454 454 0 0 1 14 25c-3.62 1.159-7.29 1.325-11 .5a559 559 0 0 1-14-24.5 21.1 21.1 0 0 0-9-1v26h-9zm9 9v21c5.76.351 11.43-.149 17-1.5a6.97 6.97 0 0 0 3.5-2.5c3.77-7.349 1.94-12.849-5.5-16.5q-7.485-.749-15-.5" style="opacity:.925"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>