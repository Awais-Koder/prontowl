  <div id="default-modal" tabindex="-1" aria-hidden="true"
      class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
      <div class="relative p-4 w-full max-w-2xl max-h-full">
          <!-- Modal content -->
          <div class="relative bg-white rounded-lg shadow">
              <!-- Modal header -->
              <div class="flex items-center justify-between p-4 md:p-5 rounded-t dark:border-gray-600">
                  <h3 class="text-xl font-semibold text-custom-green text-center w-full">
                      Help by sharing
                  </h3>
                  <button type="button"
                      class="text-gray-400 bg-transparent  hover:text-white transition-colors duration-300 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center hover:bg-custom-green"
                      data-modal-hide="default-modal">
                      <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                          viewBox="0 0 14 14">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                      </svg>
                      <span class="sr-only">Close modal</span>
                  </button>
              </div>
              <!-- Modal body -->
              @php
                  $campaignUrl = route('campaign.show', base64_encode($campaign->id));
                  $encodedUrl = urlencode($campaignUrl); // Encode URL for safe usage in query parameters
                  $campaignTitle = urlencode($campaign->title); // Use the campaign title if needed
              @endphp
              <div class="p-4 md:p-5">
                  <div class="flex justify-between items-center">
                      <p class="text-gray-500 text-lg font-semibold">
                          Share on socials:
                      </p>
                      <div class="share-icons flex justify-start w-1/2">
                          <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}" target="_blank">
                              <i
                                  class="fa-brands fa-square-facebook text-4xl p-1 rounded-lg shadow-xs mr-5 border text-[#3B5998]">
                              </i>
                          </a>
                          <a href="https://twitter.com/intent/tweet?url={{ $encodedUrl }}&text={{ $campaignTitle }}" target="_blank">
                              <i
                                  class="fa-brands fa-square-x-twitter text-4xl p-1 rounded-lg shadow-xs mr-5 border text-[#000000]"></i>
                          </a>
                          <a href="https://www.instagram.com/?url={{ urlencode($campaignUrl) }}" target="_blank">
                            <i
                                class="fa-brands fa-square-instagram text-4xl p-1 rounded-lg shadow-xs mr-5 border text-[#f04c5c]"></i>
                        </a>
                          <a
                              href="https://www.linkedin.com/shareArticle?url={{ $encodedUrl }}&title={{ $campaignTitle }}" target="_blank">
                              <i
                                  class="fa-brands fa-linkedin text-4xl p-1 rounded-lg shadow-xs mr-5 border text-[#0077b7]"></i>
                          </a>
                      </div>
                  </div>
                  <div class="flex justify-between items-center mt-8">
                      <p class="text-gray-500 text-lg font-semibold">
                          Send in messenger:
                      </p>
                      <div class="share-icons flex justify-start w-1/2">
                          <a href="https://www.facebook.com/dialog/send?link={{ $encodedUrl }}&app_id=966242223397117&redirect_uri={{ $encodedUrl }}" target="_blank">
                              <i
                                  class="fa-brands fa-facebook-messenger text-4xl p-1 rounded-lg shadow-xs mr-5 border text-[#2196f3]"></i>
                          </a>
                          <a href="https://wa.me/?text={{ $encodedUrl }}" target="_blank">
                              <i
                                  class="fa-brands fa-square-whatsapp text-4xl p-1 rounded-lg shadow-xs mr-5 border text-[#4FCE5D]"></i>
                          </a>
                      </div>
                  </div>
                  <div class="flex justify-between items-center mt-8 mb-5">
                      <p class="text-gray-500 text-lg font-semibold">
                          Other sharing:
                      </p>
                      <div class="share-icons flex justify-start w-1/2">
                          <a href="mailto:?subject={{ $campaignTitle }}&body=Check this out: {{ $campaignUrl }}"
                              target="_blank">
                              <i class="fa-solid fa-envelope text-4xl p-1 rounded-lg shadow-xs mr-5 border"></i>
                          </a>
                      </div>
                  </div>
                  <div class="flex justify-between items-center mt-8 mb-5">
                      <p class="text-gray-500 text-lg font-semibold">
                          Campaign URL
                      </p>
                      <div class="share-icons flex justify-start w-1/2">
                          <input type="text" name="" id="">
                      </div>
                  </div>
              </div>
              <!-- Modal footer -->
          </div>
      </div>
  </div>
