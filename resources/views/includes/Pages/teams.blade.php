@php
$teams = [
    [
        'img' => 'assets/img/team/team-01.jpg', 
        'name' => 'Luciana Sacchena', 
        'title' => "Product Designer", 
        'desc' => "Product designers are the creative architects behind the innovative solutions that shape our world.", 
        'class' => "group sm:grid sm:grid-cols-2 bg-[#F8F5F0] dark:bg-[#1E2A35]", 
        'data' => "", 
        'span' => "false", 
        'span1' => "true", 
    ],
    [
        'img' => 'assets/img/team/team-02.jpg', 
        'name' => 'Jamse Zuan', 
        'title' => "CEO & Founder", 
        'desc' => "As the visionary leader of our furniture eCommerce business, Emma Wilson ensures the overall strategic direction and growth of the company.", 
        'class' => "group flex flex-col-reverse sm:grid sm:grid-cols-2 bg-[#F8F5F0] dark:bg-[#1E2A35]", 
        'data' => "200", 
        'span' => "true", 
        'span1' => "false",
    ],
    [
        'img' => 'assets/img/team/team-03.jpg', 
        'name' => 'Flacxia Piano', 
        'title' => "Interior Designer", 
        'desc' => "Flacxia Piano ensures smooth daily operations and efficient logistics management. With his expertise in streamlining processes, he helps maintain a seamless supply chain", 
        'class' => "group flex flex-col-reverse lg:grid lg:grid-cols-2 bg-[#F8F5F0] dark:bg-[#1E2A35] sm:flex-row-reverse lg:flex-row", 
        'data' => "", 
        'span' => "true", 
        'span1' => "false",
    ],
    [
        'img' => 'assets/img/team/team-04.jpg', 
        'name' => 'Luciana Sacchena', 
        'title' => "Photographer", 
        'desc' => "Luciana Sacchena oversees daily operations and ensures the seamless execution of business processes. With a keen eye for logistics and a strong background", 
        'class' => "group flex flex-col lg:grid lg:grid-cols-2 bg-[#F8F5F0] dark:bg-[#1E2A35] sm:flex-row-reverse lg:flex-row", 
        'data' => "200", 
        'span' => "false", 
        'span1' => "true",
    ],
    [
        'img' => 'assets/img/team/team-05.jpg', 
        'name' => 'Nathan', 
        'title' => "Client Communication Executive", 
        'desc' => "As the technical lead, Nathan ensures our website is always fast, secure, and user-friendly. He leads the development team in creating an optimized shopping experience for every visitor.", 
        'class' => "group sm:grid sm:grid-cols-2 bg-[#F8F5F0] dark:bg-[#1E2A35]", 
        'data' => "", 
        'span' => "false", 
        'span1' => "true",
    ],
    [
        'img' => 'assets/img/team/team-06.jpg', 
        'name' => 'Porlo Matiana', 
        'title' => "Marketing Specialist", 
        'desc' => "Porlo Matiana heads our sales team, using his expertise to meet customer needs with personalized furniture recommendations. His leadership boosts our sales growth while ensuring customer satisfaction.", 
        'class' => "group flex flex-col-reverse sm:grid sm:grid-cols-2 bg-[#F8F5F0] dark:bg-[#1E2A35]", 
        'data' => "200", 
        'span' => "true", 
        'span1' => "false",
    ],
];
@endphp

@foreach ($teams as $item)
    <div class="{{ $item['class'] }}" data-aos="fade-up" data-aos-delay="{{ $item['data'] }}">
        
        @if ($item['span'] === 'false')
            <div>
                <img class="w-full object-cover h-full" src="{{ asset($item['img']) }}" alt="team member">
            </div>
        @elseif ($item['span1'] === 'true')
            <div>
                <img class="w-full object-cover h-full" src="{{ asset($item['img']) }}" alt="team member">
            </div>
        @endif

        <div class="sm:flex items-center justify-center px-5 py-[30px]">
            <div class="sm:max-w-[294px]">
                <h4 class="font-semibold leading-none text-xl md:text-2xl duration-300 transition-all group-hover:text-primary"><a href="#">{{ $item['name'] }}</a></h4>
                <span class="text-title dark:text-white-light leading-none mt-[10px] block">{{ $item['title'] }}</span>
                <p class="mt-[15px]">{{ $item['desc'] }}  </p>
                <div class="flex items-center gap-5 md:gap-6 mt-[15px]">
                    <a href="#" class="hover:text-primary dark:hover:text-primary transition-all duration-300 text-paragraph dark:text-white">
                        <svg class="fill-current" width="9" height="17" viewBox="0 0 9 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.28937 3.53966H8.75V0.986683C8.04339 0.908481 7.33293 0.870446 6.62204 0.872762C6.13033 0.844423 5.6384 0.924373 5.18072 1.10701C4.72304 1.28964 4.31069 1.57054 3.97258 1.93002C3.63446 2.2895 3.37876 2.71885 3.22339 3.18799C3.06802 3.65713 3.01675 4.15471 3.07317 4.64583V6.89464H0.75V9.74951H3.07317V16.9265H5.9218V9.74951H8.1519L8.50599 6.89464H5.9218V4.92836C5.92293 4.10357 6.14424 3.53966 7.28937 3.53966Z" />
                        </svg>
                    </a>
                    <a href="#" class="hover:text-primary dark:hover:text-primary transition-all duration-300 text-paragraph dark:text-white">
                        <svg class="fill-current" width="21" height="17" viewBox="0 0 21 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.75 2.76521C19.9975 3.08941 19.2016 3.30477 18.387 3.40465C19.245 2.90136 19.8866 2.10459 20.1903 1.16516C19.3867 1.63627 18.5076 1.96807 17.5909 2.14622C17.0284 1.55383 16.2987 1.14227 15.4966 0.964917C14.6945 0.787568 13.857 0.852629 13.0928 1.15166C12.3285 1.45069 11.6729 1.96988 11.2109 2.64185C10.7489 3.31383 10.5019 4.10756 10.5019 4.92003C10.4985 5.23059 10.5302 5.54055 10.5964 5.84415C8.96579 5.76571 7.37027 5.34808 5.91408 4.61854C4.45788 3.889 3.17378 2.86398 2.14566 1.61043C1.61819 2.50199 1.45485 3.55894 1.68897 4.56551C1.92309 5.57207 2.53702 6.45237 3.40544 7.02668C2.75654 7.00938 2.12136 6.83814 1.55343 6.52739V6.57119C1.5545 7.50705 1.88169 8.41398 2.47992 9.13935C3.07815 9.86471 3.9109 10.3642 4.83802 10.5538C4.48717 10.645 4.12561 10.6897 3.76285 10.6867C3.50216 10.6915 3.2417 10.6685 2.98601 10.618C3.25047 11.4227 3.761 12.1264 4.44719 12.6321C5.13337 13.1379 5.9614 13.4206 6.81705 13.4415C5.36459 14.5629 3.57366 15.1705 1.73065 15.1671C1.40291 15.1693 1.07535 15.1508 0.75 15.1116C2.62535 16.3044 4.81007 16.9347 7.04006 16.9263C8.57515 16.9366 10.097 16.6453 11.5173 16.0693C12.9375 15.4934 14.2279 14.6442 15.3134 13.5712C16.3989 12.4982 17.2579 11.2227 17.8405 9.81876C18.4232 8.41484 18.7179 6.91048 18.7075 5.39304C18.7075 5.21347 18.7075 5.04121 18.6927 4.86894C19.5022 4.29722 20.1994 3.5843 20.75 2.76521Z"/>
                        </svg>
                    </a>
                    <a href="#" class="hover:text-primary dark:hover:text-primary transition-all duration-300 text-paragraph dark:text-white">
                        <svg class="fill-current" width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.1119 5.26153C18.0978 4.52998 17.9609 3.80621 17.7071 3.12122C17.4832 2.5349 17.141 2.00238 16.7025 1.5577C16.264 1.11303 15.7387 0.76597 15.1604 0.538717C14.4845 0.281816 13.7704 0.143084 13.0487 0.128435C12.1155 0.0839875 11.8198 0.0737305 9.45626 0.0737305C7.09276 0.0737305 6.79704 0.0839875 5.86716 0.125016C5.14542 0.1394 4.43136 0.27814 3.75553 0.535298C3.17297 0.758372 2.64593 1.10854 2.21172 1.561C1.76994 2.00181 1.42775 2.53451 1.20876 3.12236C0.956968 3.8062 0.821246 4.52833 0.807345 5.25811C0.76012 6.2029 0.75 6.50263 0.75 8.89936C0.75 11.2961 0.76012 11.5947 0.800598 12.5361C0.814746 13.2676 0.951629 13.9914 1.20538 14.6764C1.42946 15.2628 1.77181 15.7954 2.21053 16.2401C2.64926 16.6848 3.17471 17.0318 3.75328 17.2589C4.42989 17.516 5.14469 17.6547 5.86716 17.6692C6.79592 17.7102 7.09164 17.7204 9.45513 17.7204C11.8186 17.7204 12.1143 17.7102 13.0431 17.6692C13.7649 17.6549 14.4789 17.5161 15.1547 17.2589C15.7335 17.0321 16.2591 16.6852 16.6979 16.2404C17.1366 15.7957 17.4789 15.263 17.7026 14.6764C17.956 13.9913 18.0929 13.2676 18.1074 12.5361C18.1479 11.5947 18.158 11.295 18.158 8.89936C18.158 6.50377 18.158 6.20404 18.1142 5.26266L18.1119 5.26153ZM16.549 12.4665C16.5435 13.0259 16.4423 13.58 16.2499 14.1043C16.1048 14.4848 15.8828 14.8304 15.5981 15.1189C15.3135 15.4075 14.9725 15.6325 14.597 15.7796C14.0797 15.9742 13.5331 16.0768 12.9813 16.0827C12.0626 16.1238 11.7871 16.134 9.46413 16.134C7.14111 16.134 6.86226 16.1238 5.94699 16.0827C5.39516 16.0772 4.84846 15.9746 4.33123 15.7796C3.95345 15.6389 3.61179 15.4137 3.33163 15.1208C3.04338 14.8363 2.82136 14.4902 2.68173 14.1077C2.48932 13.5834 2.38775 13.0293 2.38151 12.47C2.34103 11.5388 2.33091 11.2596 2.33091 8.90506C2.33091 6.5505 2.34103 6.26786 2.38151 5.34016C2.38698 4.78083 2.48818 4.22671 2.6806 3.70245C2.81912 3.32105 3.04135 2.97655 3.33051 2.69498C3.61108 2.40261 3.95261 2.17754 4.3301 2.03625C4.84745 1.84166 5.39408 1.7391 5.94587 1.7331C6.86451 1.69207 7.13999 1.68181 9.463 1.68181C11.786 1.68181 12.0649 1.69207 12.9801 1.7331C13.532 1.73869 14.0787 1.84126 14.5959 2.03625C14.9737 2.17688 15.3154 2.40204 15.5955 2.69498C15.8837 2.97954 16.1058 3.32566 16.2454 3.70815C16.4384 4.23081 16.5411 4.78328 16.549 5.3413C16.5895 6.27242 16.5996 6.55164 16.5996 8.9062C16.5996 11.2608 16.5895 11.5366 16.549 12.4677V12.4665Z"/>
                            <path d="M9.45726 4.36279C8.57261 4.36279 7.70782 4.62872 6.97226 5.12696C6.2367 5.62519 5.6634 6.33336 5.32486 7.16189C4.98631 7.99043 4.89773 8.90213 5.07032 9.78169C5.24291 10.6613 5.66891 11.4692 6.29445 12.1033C6.91999 12.7375 7.71698 13.1693 8.58463 13.3443C9.45229 13.5192 10.3516 13.4294 11.1689 13.0862C11.9863 12.743 12.6848 12.1619 13.1763 11.4162C13.6678 10.6706 13.9301 9.79389 13.9301 8.89709C13.9298 7.69461 13.4585 6.54147 12.6197 5.69119C11.781 4.84091 10.6434 4.3631 9.45726 4.36279ZM9.45726 11.8379C8.8835 11.8379 8.32263 11.6654 7.84557 11.3423C7.36851 11.0191 6.99668 10.5598 6.77711 10.0225C6.55755 9.48512 6.5001 8.89383 6.61204 8.32337C6.72397 7.75291 7.00025 7.22891 7.40596 6.81763C7.81167 6.40635 8.32857 6.12627 8.8913 6.0128C9.45403 5.89933 10.0373 5.95757 10.5674 6.18015C11.0975 6.40273 11.5506 6.77966 11.8693 7.26327C12.1881 7.74689 12.3582 8.31545 12.3582 8.89709C12.3579 9.67694 12.0522 10.4248 11.5082 10.9762C10.9642 11.5277 10.2265 11.8376 9.45726 11.8379Z"/>
                            <path d="M15.1516 4.18485C15.1516 4.39438 15.0904 4.5992 14.9756 4.77341C14.8608 4.94762 14.6977 5.0834 14.5068 5.16358C14.3159 5.24376 14.1059 5.26474 13.9033 5.22386C13.7006 5.18299 13.5145 5.0821 13.3684 4.93394C13.2224 4.78579 13.1229 4.59703 13.0826 4.39153C13.0423 4.18604 13.0629 3.97303 13.142 3.77945C13.2211 3.58588 13.355 3.42043 13.5267 3.30403C13.6985 3.18763 13.9005 3.12549 14.1071 3.12549C14.3841 3.12549 14.6498 3.2371 14.8457 3.43577C15.0416 3.63444 15.1516 3.90389 15.1516 4.18485Z" />
                        </svg>
                    </a>
                    <a href="#" class="hover:text-primary dark:hover:text-primary transition-all duration-300 text-paragraph dark:text-white">
                        <svg class="fill-current" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M0.15625 2.05465C0.15625 1.03522 0.972098 0.208008 1.97753 0.208008C2.98233 0.208008 3.79818 1.03522 3.79881 2.05465C3.79881 3.07408 2.98297 3.91859 1.97753 3.91859C0.972098 3.91859 0.15625 3.07408 0.15625 2.05465ZM15.3218 15.5865V15.5859H15.3256V9.94597C15.3256 7.18689 14.7398 5.06152 11.5586 5.06152C10.0292 5.06152 9.00296 5.91244 8.58397 6.71915H8.53974V5.31911H5.52344V15.5859H8.66423V10.5021C8.66423 9.16361 8.91448 7.86929 10.5493 7.86929C12.1602 7.86929 12.1842 9.39684 12.1842 10.588V15.5865H15.3218ZM0.410156 5.31885H3.55474V15.5856H0.410156V5.31885Z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        @if ($item['span1'] === 'false')
            <div>
                <img class="w-full object-cover h-full" src="{{ asset($item['img']) }}" alt="team member">
            </div>
        @elseif ($item['span'] === 'true')
            <div>
                <img class="w-full object-cover h-full" src="{{ asset($item['img']) }}" alt="team member">
            </div>
        @endif

    </div>
@endforeach