<x-app-layout>
    <!--　固定ヘッダー -->
    <div class="w-full fixed top-0 left-0 z-50 px-8 py-4 bg-white shadow-md flex justify-between items-center">
        <a href="{{ route('admin.hospitals.index') }}"
            class="text-base font-bold text-gray-800 hover:underline">精神科評価サイト</a>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="text-base font-bold text-gray-800 hover:underline">管理者ログアウト</button>
        </form>
    </div>


    <div class="bg-sky-400 bg-opacity-30 min-h-screen px-4 py-24">
        <div class="bg-white rounded-lg shadow-md max-w-2xl mx-auto p-6">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 border border-green-400 p-4 rounded mb-4 text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 text-red-800 text-base p-4 mb-4 rounded">入力に誤りがあります。内容をご確認ください。</div>
            @endif
            <h2 class="text-xl font-bold text-center text-gray-800">病院情報編集フォーム</h2>
            <form method="POST" action="{{ route('admin.hospitals.update', $hospital->id) }}">
                @csrf
                @method('PUT')

                <!-- 病院名 -->
                <label class="block font-medium">病院名</label>
                <input type="text" name="name" value="{{ old('name', $hospital->name) }}"
                    class="w-full border px-4 py-2 rounded mt-2 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-600 text-base mt-2">{{ $message }}</p>
                @enderror

                <!-- 所在地 -->
                <label class="block font-medium mt-4">所在地</label>
                <input type="text" name="address" value="{{ old('address', $hospital->address) }}"
                    class="w-full border px-4 py-2 rounded mt-2">
                @error('address')
                    <p class="text-red-600 text-base mt-2">{{ $message }}</p>
                @enderror

                <!-- 都道府県 -->
                <label class="block font-medium mt-4">都道府県</label>
                <select name="prefecture" class="w-full border px-4 py-2 rounded mt-2">
                    @foreach ($prefectures as $pref)
                        <option value="{{ $pref }}"
                            {{ old('prefecture', $hospital->prefecture) == $pref ? 'selected' : '' }}>
                            {{ $pref }}</option>
                    @endforeach
                </select>

                <!-- 最寄駅 -->
                <label class="block font-medium mt-4">最寄駅</label>
                <input type="text" name="station" value="{{ old('station', $hospital->station) }}"
                    class="w-full border px-4 py-2 rounded mt-2" placeholder="例: ○○駅">


                <!-- 病院タイプ -->
                <label class="block font-medium mt-4">病院タイプ</label>
                <div class="flex gap-6 mt-2">
                    <label>
                        <input type="radio" name="type" value="hospital"
                            {{ old('type', $hospital->type) === 'hospital' ? 'checked' : '' }}>
                        病院
                    </label>
                    <label>
                        <input type="radio" name="type" value="clinic"
                            {{ old('type', $hospital->type) === 'clinic' ? 'checked' : '' }}>
                        クリニック
                    </label>
                </div>

                <!-- 診療曜日/時間 -->
                <label class="block font-medium mt-4">診療曜日</label>
                <input type="text" name="day_of_week" value="{{ old('day_of_week', $hospital->day_of_week) }}"
                    placeholder="例: 月火水木金" class="w-full border px-4 py-2 rounded mt-2">
                @error('day_of_week')
                    <p class="text-red-600 text-base mt-2">{{ $message }}</p>
                @enderror

                <label class="block font-medium mt-4">診療時間（午前）</label>
                <input type="text" name="am_open" value="{{ old('am_open', $hospital->am_open) }}"
                    placeholder="例: 08:30〜12:00" class="w-full border px-4 py-2 rounded mt-2">
                @error('am_open')
                    <p class="text-red-600 text-base mt-2">{{ $message }}</p>
                @enderror

                <label class="block font-medium mt-4">診療時間（午後）</label>
                <input type="text" name="pm_open" value="{{ old('pm_open', $hospital->pm_open) }}"
                    placeholder="例: 13:00〜17:00" class="w-full border px-4 py-2 rounded mt-2">
                @error('pm_open')
                    <p class="text-red-600 text-base mt-2">{{ $message }}</p>
                @enderror

                <!-- 治療法 -->
                <label class="block font-medium mt-6">治療法</label>
                <div id="treatment-display-area" class="tag-area border p-2 bg-gray-100 rounded mb-2"></div>
                <input id="treatment-input-field" value="{{ old('treatment', $hospital->treatment) }}" type="text"
                    placeholder="タグをクリックしてください" class="w-full tag-input border px-4 py-2 rounded">
                <input type="hidden" name="treatment" value="{{ old('treatment', $hospital->treatment) }}"
                    id="treatment-hidden-input">
                <div id="treatment-suggestions-data" style="display:none">@json($treatments)</div>

                <!-- 専門分野 -->
                <label class="block font-medium mt-6">専門分野</label>
                <div id="specialties-display-area" class="tag-area border p-2 bg-gray-100 rounded mb-2"></div>
                <input id="specialties-input-field"
                    value="{{ old('specialties', $hospital->specialties->pluck('name')->implode(',')) }}"
                    type="text" placeholder="タグをクリックしてください" class="w-full tag-input border px-4 py-2 rounded">
                <input type="hidden" name="specialties"
                    value="{{ old('specialties', $hospital->specialties->pluck('name')->implode(',')) }}"
                    id="specialties-hidden-input">
                <div id="specialties-suggestions-data" style="display:none">@json($specialties)</div>
                @error('specialties')
                    <p class="text-red-600 text-base mt-2">{{ $message }}</p>
                @enderror

                <!-- 対象疾患 -->
                <label class="block font-medium mt-6">対象疾患</label>
                <div id="disorders-display-area" class="tag-area border p-2 bg-gray-100 rounded mb-2"></div>
                <input id="disorders-input-field"
                    value="{{ old('disorders', $hospital->disorders->pluck('name')->implode(',')) }}" type="text"
                    placeholder="タグをクリックしてください" class="w-full tag-input border px-4 py-2 rounded">
                <input type="hidden" name="disorders"
                    value="{{ old('disorders', $hospital->disorders->pluck('name')->implode(',')) }}"
                    id="disorders-hidden-input">
                <div id="disorders-suggestions-data" style="display:none">@json($disorders)</div>
                @error('disorders')
                    <p class="text-red-600 text-base mt-2">{{ $message }}</p>
                @enderror

                <!-- 特徴タグ -->
                <label class="block font-medium mt-6">特徴タグ</label>
                <div id="feature-display-area" class="tag-area border p-2 bg-gray-100 rounded mb-2"></div>
                <input id="feature-input-field" value="{{ old('feature', $hospital->feature) }}" type="text"
                    placeholder="タグをクリックしてください" class="w-full tag-input border px-4 py-2 rounded">
                <input type="hidden" name="feature" value="{{ old('feature', $hospital->feature) }}"
                    id="feature-hidden-input">
                <div id="feature-suggestions-data" style="display:none">@json($features)</div>

                <script>
                    // まずはページ全体が読み込まれてから実行されるようにする。
                    document.addEventListener("DOMContentLoaded", function() {
                        // jsonからデータを取得する。　JSON .parseやtextContentが少々不明
                        const treatmentSuggestions = JSON.parse(document.getElementById("treatment-suggestions-data")
                            .textContent);
                        const specialtiesSuggestions = JSON.parse(document.getElementById("specialties-suggestions-data")
                            .textContent);
                        const disordersSuggestions = JSON.parse(document.getElementById("disorders-suggestions-data")
                            .textContent);
                        const featureSuggestions = JSON.parse(document.getElementById("feature-suggestions-data").textContent);


                        // 各タグを取得する共通化された関数を作成
                        function initTagEditor(fieldIdPrefix, suggestions) {
                            // タグを取得していく
                            const displayArea = document.getElementById(`${fieldIdPrefix}-display-area`); // 表示エリア
                            const inputField = document.getElementById(`${fieldIdPrefix}-input-field`);
                            const hiddenInput = document.getElementById(`${fieldIdPrefix}-hidden-input`);

                            // ページ読み込み後、タグを表示させる
                            suggestions.forEach(item => {
                                // 繰り返し処理で新しくタグを作成する
                                const tag = document.createElement("div");
                                //　定数tag（作成したdivタグ）にクラスメイをtagと命名
                                tag.classList.add("tag");
                                // tagのテキストにitem.nameを代入
                                tag.textContent = typeof item === "string" ? item : item.name;

                                // クリックで選択欄に追加する
                                tag.addEventListener("click", function() {
                                    const text = typeof item === "string" ? item : item.name;

                                    // filter(Boolean)でカンマの位置を制御？
                                    const current = inputField.value.split(",").map(t => t.trim()).filter(
                                        Boolean);
                                    if (!current.includes(text)) {
                                        current.push(text);
                                        inputField.value = current.join(",");
                                        hiddenInput.value = inputField.value;
                                    }


                                });

                                // 画面に表示する
                                displayArea.appendChild(tag);
                            });
                        }
                        // hidden-input(データ送信を行うため)を更新するための関数を共通化
                        function updateHiddenInput(fieldIdPrefix) {
                            const displayArea = document.getElementById(`${fieldIdPrefix}-display-area`); // 表示エリア
                            const hiddenInput = document.getElementById(`${fieldIdPrefix}-hidden-input`); // 送信エリア

                            // 表示エリアに表示された"tag"を全て取得
                            const allTags = displayArea.querySelectorAll(".tag");
                            // そのtagを全て配列かして、、、処理内容不明。特にchildNodes[0].nodeValue.ここの部分。
                            const names = Array.from(allTags).map(tag => {
                                return tag.childNodes[0].nodeValue.trim();
                            });

                            hiddenInput.value = names.join(",");
                        }

                        // 各タグ取得のために共通化された関数に各タグの情報を与える
                        initTagEditor("treatment", treatmentSuggestions);
                        initTagEditor("specialties", specialtiesSuggestions);
                        initTagEditor("disorders", disordersSuggestions);
                        initTagEditor("feature", featureSuggestions);

                        ["treatment", "specialties", "disorders", "feature"].forEach(field => {
                            const inputField = document.getElementById(`${field}-input-field`);
                            const hiddenInput = document.getElementById(`${field}-hidden-input`);
                            if (inputField && hiddenInput) {
                                hiddenInput.value = inputField.value;
                            }
                        });

                    });
                </script>


                <!-- その他情報 -->
                <label class="block font-medium mt-6">HP</label>
                <input type="text" name="homepage_url" value="{{ old('homepage_url', $hospital->homepage_url) }}"
                    class="w-full border px-4 py-2 rounded mt-2">
                @error('homepage_url')
                    <p class="text-red-600 text-base mt-2">{{ $message }}</p>
                @enderror

                <label class="block font-medium mt-4">地図URL</label>
                <input type="text" name="map_url" value="{{ old('map_url', $hospital->map_url) }}"
                    class="w-full border px-4 py-2 rounded mt-2">
                @error('map_url')
                    <p class="text-red-600 text-base mt-2">{{ $message }}</p>
                @enderror

                <label class="block font-medium mt-4">電話番号</label>
                <input type="text" name="phone" value="{{ old('phone', $hospital->phone) }}"
                    class="w-full border px-4 py-2 rounded mt-2">
                @error('phone')
                    <p class="text-red-600 text-base mt-2">{{ $message }}</p>
                @enderror

                <div class="flex justify-between mt-6">
                    <a href="{{ route('admin.hospitals.show', $hospital->id) }}"
                        class="bg-orange-400 text-white px-6 py-2 rounded hover:bg-orange-500">{{ $hospital->name }}の詳細画面へ</a>
                    <button type="submit"
                        class="bg-orange-400 text-white px-6 py-2 rounded hover:bg-orange-500">保存する</button>
                </div>
            </form>
        </div>
    </div>
    <footer class="bg-white text-center py-6">
        <a href="{{ route('admin.hospitals.index') }}" class="text-base tetext-gray-800 font-bold hover:underline">
            精神科評価サイト
        </a>
    </footer>
</x-app-layout>
