<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: server.proto

namespace GPBMetadata;

class Server
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        \GPBMetadata\Physics::initOnce();
        $pool->internalAddGeneratedFile(hex2bin(
            "0af30c0a0c7365727665722e70726f746f12046c75676f228e010a0b4a6f" .
            "696e52657175657374120d0a05746f6b656e18012001280912180a107072" .
            "6f746f636f6c5f76657273696f6e18022001280912220a097465616d5f73" .
            "69646518032001280e320f2e6c75676f2e5465616d2e53696465120e0a06" .
            "6e756d62657218042001280d12220a0d696e69745f706f736974696f6e18" .
            "052001280b320b2e6c75676f2e506f696e7422bc020a0c47616d65536e61" .
            "7073686f7412270a05737461746518012001280e32182e6c75676f2e4761" .
            "6d65536e617073686f742e5374617465120c0a047475726e18022001280d" .
            "121d0a09686f6d655f7465616d18032001280b320a2e6c75676f2e546561" .
            "6d121d0a09617761795f7465616d18042001280b320a2e6c75676f2e5465" .
            "616d12180a0462616c6c18052001280b320a2e6c75676f2e42616c6c121f" .
            "0a177475726e735f62616c6c5f696e5f676f616c5f7a6f6e651806200128" .
            "0d12230a0a73686f745f636c6f636b18072001280b320f2e6c75676f2e53" .
            "686f74436c6f636b22570a055374617465120b0a0757414954494e471000" .
            "120d0a094745545f52454144591001120d0a094c495354454e494e471002" .
            "120b0a07504c4159494e471003120c0a085348494654494e47100412080a" .
            "044f5645521063227d0a045465616d121d0a07706c617965727318012003" .
            "280b320c2e6c75676f2e506c61796572120c0a046e616d65180220012809" .
            "120d0a0573636f726518032001280d121d0a047369646518042001280e32" .
            "0f2e6c75676f2e5465616d2e53696465221a0a045369646512080a04484f" .
            "4d45100012080a0441574159100122480a0953686f74436c6f636b12220a" .
            "097465616d5f7369646518062001280e320f2e6c75676f2e5465616d2e53" .
            "69646512170a0f72656d61696e696e675f7475726e7318072001280d22b5" .
            "010a06506c61796572120e0a066e756d62657218012001280d121d0a0870" .
            "6f736974696f6e18022001280b320b2e6c75676f2e506f696e7412200a08" .
            "76656c6f6369747918032001280b320e2e6c75676f2e56656c6f63697479" .
            "12220a097465616d5f7369646518042001280e320f2e6c75676f2e546561" .
            "6d2e5369646512220a0d696e69745f706f736974696f6e18052001280b32" .
            "0b2e6c75676f2e506f696e7412120a0a69735f6a756d70696e6718062001" .
            "280822650a0442616c6c121d0a08706f736974696f6e18012001280b320b" .
            "2e6c75676f2e506f696e7412200a0876656c6f6369747918022001280b32" .
            "0e2e6c75676f2e56656c6f63697479121c0a06686f6c6465721803200128" .
            "0b320c2e6c75676f2e506c6179657222ab010a0d4f72646572526573706f" .
            "6e7365122c0a04636f646518012001280e321e2e6c75676f2e4f72646572" .
            "526573706f6e73652e537461747573436f6465120f0a0764657461696c73" .
            "180220012809225b0a0a537461747573436f6465120b0a07535543434553" .
            "53100012120a0e554e4b4e4f574e5f504c41594552100112110a0d4e4f54" .
            "5f4c495354454e494e471002120e0a0a57524f4e475f5455524e10031209" .
            "0a054f544845521063224c0a084f72646572536574120c0a047475726e18" .
            "012001280d121b0a066f726465727318022003280b320b2e6c75676f2e4f" .
            "7264657212150a0d64656275675f6d657373616765180320012809228301" .
            "0a054f72646572121a0a046d6f766518012001280b320a2e6c75676f2e4d" .
            "6f76654800121c0a05636174636818022001280b320b2e6c75676f2e4361" .
            "7463684800121a0a046b69636b18032001280b320a2e6c75676f2e4b6963" .
            "6b4800121a0a046a756d7018042001280b320a2e6c75676f2e4a756d7048" .
            "0042080a06616374696f6e22280a044d6f766512200a0876656c6f636974" .
            "7918012001280b320e2e6c75676f2e56656c6f6369747922070a05436174" .
            "636822280a044b69636b12200a0876656c6f6369747918012001280b320e" .
            "2e6c75676f2e56656c6f6369747922280a044a756d7012200a0876656c6f" .
            "6369747918012001280b320e2e6c75676f2e56656c6f63697479326f0a04" .
            "47616d6512340a094a6f696e415465616d12112e6c75676f2e4a6f696e52" .
            "6571756573741a122e6c75676f2e47616d65536e617073686f7430011231" .
            "0a0a53656e644f7264657273120e2e6c75676f2e4f726465725365741a13" .
            "2e6c75676f2e4f72646572526573706f6e736542235a216769746875622e" .
            "636f6d2f6c75676f626f74732f6c75676f34676f2f70726f746f62067072" .
            "6f746f33"
        ));

        static::$is_initialized = true;
    }
}

