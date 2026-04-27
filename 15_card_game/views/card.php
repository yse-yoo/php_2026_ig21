<?php if (!isset($card)): ?>
    <div class="text-center mt-10">
        <p class="text-gray-500 mb-4">
            TODOを完成させるとカードが表示されます
        </p>
    </div>
<?php else: ?>
    <div class="relative group">
        <!-- メイン画像枠: アスペクト比 3:4 (TCG標準に近い) で固定 -->
        <div class="card-image-frame rounded border-2 border-slate-700/50 aspect-[3/4] relative overflow-hidden bg-slate-950 shadow-2xl">
            <img src="<?= $card->image ?>" alt="<?= $card->name ?>" class="w-full h-full object-cover group-hover:scale-110 duration-700 opacity-90 group-hover:opacity-100">

            <!-- 可読性向上のためのグラデーション -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-slate-950/40"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-slate-950/40 via-transparent to-transparent"></div>

            <!-- 名前・レベル・属性 (上部) -->
            <div class="absolute top-0 left-0 right-0 p-2 flex justify-between items-start gap-1">
                <div class="bg-slate-950/60 backdrop-blur-sm px-2 py-1 rounded border border-white/10 shadow-lg max-w-[70%]">
                    <h4 class="text-[10px] font-game font-black text-white leading-tight truncate group-hover:text-sky-400 transition-colors">
                        <?= $card->name ?>
                    </h4>
                </div>
                <div class="flex flex-col items-end gap-1">
                    <span class="text-[7px] font-game font-bold px-1.5 py-0.5 rounded-sm bg-sky-900/80 text-sky-200 border border-sky-400/30 uppercase leading-none backdrop-blur-sm">
                        <?= $card->element ?>
                    </span>
                    <span class="text-[8px] font-game text-white font-bold uppercase tracking-tighter bg-slate-950/60 px-1 py-0.5 rounded-sm backdrop-blur-sm border border-white/10">LV.<?= $card->level ?></span>
                </div>
            </div>

            <!-- ステータス・必殺技 (下部) -->
            <div class="absolute bottom-0 left-0 right-0 p-2 space-y-2">
                <!-- 必殺技 -->
                <div class="bg-slate-950/60 backdrop-blur-md p-1.5 rounded-sm border border-white/5 shadow-lg">
                    <div class="flex justify-between items-center mb-0.5">
                        <span class="text-[6px] font-game text-sky-400 uppercase tracking-tighter leading-none">Ultimate Skill</span>
                        <span class="text-[7px] font-game text-sky-500 font-bold leading-none">P.<?= $card->specialSkillPower ?></span>
                    </div>
                    <p class="text-[9px] font-bold text-slate-200 truncate leading-tight"><?= $card->specialSkill ?></p>
                </div>

                <!-- パラメータ -->
                <div class="flex gap-2">
                    <div class="flex-1 bg-rose-950/80 backdrop-blur-sm p-1 rounded-sm border border-rose-500/30 flex flex-col items-center shadow-md">
                        <span class="text-[5px] font-game text-rose-300 uppercase leading-none mb-0.5">Attack</span>
                        <span class="text-[11px] font-game font-bold text-rose-100 leading-none"><?= $card->attack ?></span>
                    </div>
                    <div class="flex-1 bg-sky-950/80 backdrop-blur-sm p-1 rounded-sm border border-sky-500/30 flex flex-col items-center shadow-md">
                        <span class="text-[5px] font-game text-sky-300 uppercase leading-none mb-0.5">MP Cost</span>
                        <span class="text-[11px] font-game font-bold text-sky-100 leading-none"><?= $card->mp ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>