<x-app-layout>

    <!--　固定ヘッダー -->
    <div class="w-full fixed top-0 left-0 z-50 px-8 py-4 bg-white shadow-md flex justify-between items-center">
        <a href="{{ route('hospital.index') }}" class="text-base text-gray-800 font-bold hover:underline">
            精神科評価サイト
        </a>
        <a href="{{ route('admin.login.form') }}" class="text-base text-gray-800 font-bold hover:underline">
            管理者ログイン
        </a>
    </div>


    <div class="py-32 text-center border-b border-gray-300 bg-brand-100 bg-opacity-30 rounded-lg">
        <p class="text-[40px] text-gray-800 font-semibold">病院の詳細情報</p>
    </div>

    <!--
<div class="pb-6 sm:pb-8 lg:pb-12 border-b border-gray-300 bg-brand-100 bg-opacity-30 p-6 rounded-lg">
  <div class="relative mx-auto max-w-screen-2xl px-4 md:px-8">
    
    
    <div class="absolute right-[-1vw] top-[20%] w-[35vw] h-[25vw] bg-green-200 rounded-full blur-[120px] opacity-70 z-0"></div>

    <section class="relative min-h-[600px] flex flex-col justify-between gap-6 sm:gap-10 md:gap-16 lg:flex-row z-10">
      
      <div class="flex flex-col justify-center sm:text-center lg:py-12 lg:text-left xl:w-5/12 xl:py-24">
        <p class="text-[40px] font-bold text-gray-800 tracking-wide">病院の詳細情報</p>
      </div>
    </section>
  </div>
</div>
-->


    <div class="bg-brand-100 bg-opacity-30 min-h-screen py-10 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-transparent shadow rounded-lg p-6 mb-6">
                <div class="flex lg:flex-row gap-6">
                    <!-- 左側 -->
                    <div class="lg:w-1/2 space-y-10">
                        <h1 class="text-[32px] font-semibold text-center text-gray-800 my-6">{{ $hospital->name }}</h1>
                        <!--ダミー画像。画像のサイズを固定。はみ出た部分は表示しない。-->
                        <div
                            class="bg-gray-200 w-[500px] h-[400px] flex items-center justify-center mx-auto overflow-hidden rounded">
                            <img src="https://picsum.photos/seed/{{ uniqid() }}/{{ rand(400, 800) }}/{{ rand(300, 600) }}"
                                class="object-cover w-full h-full" />
                        </div>
                        <div>
                            <p class="text-2xl font-semibold mb-4 text-center">診療時間</p>
                            <table class="table-auto w-[500px] text-center border border-gray-400 mx-auto">
                                <thead class="bg-blue-100">
                                    <tr>
                                        <th></th>
                                        @foreach (['月', '火', '水', '木', '金', '土', '日', '祝'] as $day)
                                            <th>{{ $day }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($hospital->am_open))
                                        <tr>
                                            <td>{{ $hospital->am_open }}</td>
                                            @foreach (['月', '火', '水', '木', '金', '土', '日', '祝'] as $day)
                                                <td>{{ str_contains($hospital->day_of_week, $day) ? '●' : '-' }}</td>
                                            @endforeach
                                        </tr>
                                    @endif

                                    @if (!empty($hospital->pm_open))
                                        <tr>
                                            <td>{{ $hospital->pm_open }}</td>
                                            @foreach (['月', '火', '水', '木', '金', '土', '日', '祝'] as $day)
                                                <td>{{ str_contains($hospital->day_of_week, $day) ? '●' : '-' }}</td>
                                            @endforeach
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="bg-gray-200 w-[500px] h-[400px] flex items-center justify-center mx-auto">
                            <iframe class=""
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7133.418955402901!2d139.76447358931546!3d35.68110313679508!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188bfbd89f700b%3A0x277c49ba34ed38!2z5p2x5Lqs6aeF!5e0!3m2!1sja!2sjp!4v1748863094072!5m2!1sja!2sjp"
                                width="500" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>

                    <!-- 右側。ここの箇所のみ8の倍数以外を使用しています。８の倍数だと大きすぎたり、小さすぎたりといい塩梅のサイズ感にならなかったためです。 -->
                    <div class="lg:w-1/2 space-y-6 text-base text-gray-700 mt-16">
                        <p class="text-lg"><span class="font-semibold text-lg">病院名：</span>{{ $hospital->name }}</p>
                        <p class="text-lg"><span class="font-semibold text-lg">所在地：</span>{{ $hospital->address }}</p>
                        <p class="text-lg"><span class="font-semibold text-lg">最寄駅：</span>{{ $hospital->station }}</p>
                        <p class="text-lg"><span class="font-semibold text-lg">電話番号：</span>{{ $hospital->phone }}</p>
                        <p class="text-lg"><span class="font-semibold text-lg">HP：</span>{{ $hospital->homepage_url }}
                        </p>
                        <p class="text-lg"><span
                                class="font-semibold text-lg">専門外来：</span>{{ $hospital->specialties->pluck('name')->join('、') }}
                        </p>
                        <p class="text-lg"><span
                                class="font-semibold text-lg">対象疾患：</span>{{ $hospital->disorders->pluck('name')->join('、') }}
                        </p>
                        <p class="text-lg"><span class="font-semibold text-lg">治療法：</span>{{ $hospital->treatment }}
                        </p>
                        <p class="text-lg"><span class="font-semibold text-lg">特徴：</span>{{ $hospital->feature }}</p>
                        <p class="text-lg"><span
                                class="font-semibold text-lg">口コミ平均：</span>★{{ number_format($hospital->reviews->avg('rating'), 1) }}（{{ $hospital->reviews->count() }}件）
                        </p>

                        @foreach ($hospital->reviews as $review)
                            <div class="bg-gray-100 border border-gray-300 p-2 rounded">
                                <p>★{{ $review->rating }}</p>
                                <p>{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-white text-center py-6">
        <a href="{{ route('hospital.index') }}" class="text-base tetext-gray-800 font-bold hover:underline">
            精神科評価サイト
        </a>
    </footer>

    </footer>
</x-app-layout>
