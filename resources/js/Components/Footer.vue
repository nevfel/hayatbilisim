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
                    <a :href="route('kvkk')" class="link link-hover">KVKK</a>
                    <a :href="route('terms')" class="link link-hover">Kullanım Şartları</a>
                    <a :href="route('privacy')" class="link link-hover">Gizlilik Politikası</a>
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
                            <div class="bg-white rounded px-3 py-2 shadow-sm hover:shadow-md transition-shadow border border-base-300 flex items-center justify-center" title="Visa">
                                <svg width="48" height="16" viewBox="0 0 48 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="48" height="16" rx="2" fill="white"/>
                                    <path d="M20.5 5.5h-3.5l-2.2 5h3.5l2.2-5zm8.5 3.3c0-1.2-.7-1.9-1.8-1.9-.8 0-1.3.2-1.7.4l.2 1.5c.3-.2.7-.3 1.2-.3.4 0 .7.2.7.5 0 .4-.7.7-1.3.9-.7.2-1.3.5-1.3 1.3 0 .8.7 1.2 1.6 1.2.7 0 1.1-.2 1.5-.4l-.2-1.4c-.2.1-.6.2-1.1.2-.4 0-.7-.2-.7-.4 0-.4.6-.6 1.2-.8.7-.2 1.3-.5 1.3-1.2zm8.1-1.7h-1.6c-.5 0-.8.2-1 .6l-3.5 4.8h2.1l.5-1.3h2.5l.3 1.3h1.8l-1.2-5.4zm-2.4 3.3l1-3 0.6 1.5-1.6 1.5zm-6.1-3.3l-1.5 5.4h1.9l1.5-5.4h-1.9z" fill="#1434CB"/>
                                    <path d="M10.5 5.5L8.2 10.9h-2L4.1 5.5h2.1l1.1 3.1c.2.5.3 1 .4 1.4h.1c.1-.4.2-.9.4-1.4L9.4 5.5h1.1z" fill="#1434CB"/>
                                </svg>
                            </div>
                            
                            <!-- Mastercard -->
                            <div class="bg-white rounded px-3 py-2 shadow-sm hover:shadow-md transition-shadow border border-base-300 flex items-center justify-center" title="Mastercard">
                                <svg width="48" height="16" viewBox="0 0 48 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="48" height="16" rx="2" fill="white"/>
                                    <circle cx="14" cy="8" r="5" fill="#EB001B"/>
                                    <circle cx="22" cy="8" r="5" fill="#F79E1B"/>
                                    <path d="M18 3.5c1.4 0 2.6.5 3.5 1.3-1.1 1.4-1.8 3.2-1.8 5.2s.7 3.8 1.8 5.2c-.9.8-2.1 1.3-3.5 1.3-2.8 0-5-2.2-5-5s2.2-5 5-5z" fill="#FF5F00"/>
                                </svg>
                            </div>
                            
                            <!-- Troy -->
                            <div class="bg-white rounded px-3 py-2 shadow-sm hover:shadow-md transition-shadow border border-base-300 flex items-center justify-center" title="Troy">
                                <svg width="48" height="16" viewBox="0 0 48 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="48" height="16" rx="2" fill="white"/>
                                    <path d="M24 4c-1.4 0-2.5 1.1-2.5 2.5s1.1 2.5 2.5 2.5 2.5-1.1 2.5-2.5-1.1-2.5-2.5-2.5zm0 3.5c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1zm-3.5-2h-1.2v4.5h1.2v-1.8h.9c.6 0 1.1-.5 1.1-1.1s-.5-1.1-1.1-1.1h-.9zm7 0h-1.2v4.5h1.2v-1.8h.9c.6 0 1.1-.5 1.1-1.1s-.5-1.1-1.1-1.1h-.9z" fill="#1A1F71"/>
                                    <path d="M12 4h-1.2v4.5H12V6.2h.9c.6 0 1.1-.5 1.1-1.1S13.5 4 12.9 4H12zm16 0h-1.2v4.5H28V6.2h.9c.6 0 1.1-.5 1.1-1.1S29.5 4 28.9 4H28z" fill="#1A1F71"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>