<?php
use App\Models\Grammar\GrammarLesson;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$lesson = GrammarLesson::find(13);
if ($lesson) {
    $html = '
<h3>IV. Câu mệnh lệnh, câu yêu cầu, câu đề nghị, lời khuyên, v.v. trong lời nói gián tiếp</h3>
<p>Câu mệnh lệnh, câu yêu cầu, câu đề nghị, lời khuyên, v.v. trong lời nói gián tiếp thường được tường thuật lại bằng động từ nguyên mẫu có to (to-infinitive) hoặc tân ngữ + động từ nguyên mẫu có to (object + to-infinitive).</p>

<h4>1. Câu mệnh lệnh và câu yêu cầu (Orders and requests)</h4>
<p>Để chuyển câu mệnh lệnh, câu yêu cầu từ trực tiếp sang gián tiếp ta làm như sau:</p>
<ul>
  <li>Dùng động từ giới thiệu <i>ask</i>, hoặc <i>tell</i>.</li>
  <li>Đặt tân ngữ (object) chỉ người nhận lệnh hoặc người được yêu cầu sau động từ giới thiệu.</li>
  <li>Dùng dạng nguyên mẫu có to (to-infinitive) của động từ trong câu trực tiếp. Trong câu phủ định, <b>not</b> được đặt trước to-infinitive.</li>
  <li>Đổi các đại từ, tính từ sở hữu và bỏ từ "please" (nếu có).</li>
</ul>

<div class="card bg-light border p-3 my-3 text-center" style="border-radius: 12px; border-left: 5px solid #ef4444 !important;">
  <span class="grammar-highlight hl-red" data-category="red" data-name="Công thức câu gián tiếp mệnh lệnh">Subject + asked/told + object (+ not) + to-infinitive</span>
</div>

<div class="mb-3">
  <b>Ex:</b> "Stay in bed for a few days," the doctor said to me.<br>
  → <span class="grammar-highlight hl-green" data-category="green" data-name="Bác sĩ yêu cầu/bảo tôi nằm nghỉ vài ngày.">The doctor asked/told me to stay in bed for a few days.</span>
</div>

<div class="mb-3">
  <b>Ex:</b> "Please don\'t tell anybody what happened," Ann said to Jim.<br>
  → <span class="grammar-highlight hl-green" data-category="green" data-name="Ann yêu cầu Jim đừng nói cho ai biết những gì đã xảy ra.">Ann asked Jim not to tell anybody what happened.</span>
</div>

<div class="mb-3">
  <b>Ex:</b> "Would you mind turning the music down?" He said to his neighbors.<br>
  → <span class="grammar-highlight hl-green" data-category="green" data-name="Anh ấy yêu cầu những người hàng xóm vặn nhỏ nhạc.">He asked his neighbors to turn the music down.</span>
</div>

<hr>

<p><b>Quy tắc quan trọng:</b></p>
<ol>
  <li><span class="grammar-highlight hl-blue" data-category="blue" data-name="Quy tắc lùi thì">Thay đổi thì của động từ (lùi thì) khi động từ dẫn ở quá khứ.</span></li>
  <li><span class="grammar-highlight hl-blue" data-category="blue" data-name="Quy tắc đổi đại từ">Thay đổi đại từ nhân xưng, đại từ sở hữu và tính từ sở hữu phù hợp ngữ cảnh.</span></li>
  <li><span class="grammar-highlight hl-blue" data-category="blue" data-name="Quy tắc đổi trạng ngữ">Thay đổi trạng từ chỉ thời gian và nơi chốn (here -> there, now -> then, tomorrow -> the next day).</span></li>
</ol>
';
    $lesson->content = $html;
    $lesson->example = null;
    $lesson->save();
    echo "Lesson 13 successfully updated to Rich HTML textbook format.\n";
} else {
    echo "Lesson 13 not found.\n";
}
