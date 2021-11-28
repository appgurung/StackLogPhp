# StackLogPhp
StackLog Php library to push logs to cloud

require_once “vendor/autoload.php”;
use stacklogio\StackLog;
$stacklog = new StackLog(“sk_jli9t5rqlcd8qzvox4fffzd8luri66",“bk_dx8xonr4m1rnbqub4moj2ol7ldwmnt”);
print ($stacklog->info(“stephen info testing”));
