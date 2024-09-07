<?php

namespace Lugo4php;


use Lugo4php\Interfaces\IBot;
use Grpc\ChannelCredentials;
use Lugo4php\Interfaces\IClient;
use Lugo\GameClient;
use Lugo\GameSnapshot;
use Lugo\GameSnapshot_State;
use Lugo\JoinRequest;
use Lugo\OrderSet;
use Lugo4php\PlayerState;
use Lugo4php\Side;

const PROTOCOL_VERSION = "1.0.0";

class Client implements IClient {
    private GameClient $client;

    public function __construct(
        private string $serverAdd,
        private bool $grpcInsecure,
        private string $token,
        private Side $side,
        private int $number,
        private Point $initPosition
    ) {}

    public function playAsBot(IBot $bot): void {        
		$this->client = new GameClient($this->serverAdd, [
            'credentials' => ChannelCredentials::createInsecure(),
            'primary_user_agent' => 'agent ' . uniqid()
        ]);

        $timeout = 5 * 1000000;

        $ok = $this->client->waitForReady($timeout);

        if (!$ok) throw new \RuntimeException("Falha ao conectar ao servidor de jogo em '{$this->serverAdd}'\n");

        echo "Conectado ao servidor gRPC {$this->side->toString()}-{$this->number}\n";

        $req = new JoinRequest();
        $req->setToken($this->token);
        $req->setProtocolVersion(PROTOCOL_VERSION);
        $req->setTeamSide($this->side->value);
        $req->setNumber($this->number);
        $req->setInitPosition($this->initPosition->toLugoPoint());

        $running = $this->client->joinATeam($req);

        $responses = $running->responses();

        foreach ($responses as $response) {
            if (!$response instanceof GameSnapshot) continue;

            /**
             * O jogo redefine a posição dos jogadores para iniciar a partida ou para reiniciá-la após um gol.
             */
            if($response->getState() === GameSnapshot_State::GET_READY) {
                $inspector = new GameInspector($this->side, $this->number, $response);
                $bot->onReady($inspector);
            }
            
            /**
             * O jogo está esperando que todos os jogadores estejam conectados. 
             * Há um limite de tempo configurável para esperar os jogadores. Após esse limite expirar, a partida é considerada encerrada.
             */
            if($response->getState() === GameSnapshot_State::WAITING) {
                // 
            }
            
            /**
             * O jogo está esperando por ordens dos jogadores. 
             * Há uma janela de tempo configurável para esta fase. Após o tempo limite expirar, 
             * o servidor ignorará as ordens faltantes e processará as que recebeu.
             */
            if($response->getState() === GameSnapshot_State::LISTENING) {
                $inspector = new GameInspector($this->side, $this->number, $response);
                $this->onListening($inspector, $bot);
            }
            
            /**
             * O jogo executa as ordens dos jogadores na mesma sequência em que foram recebidas.
             */
            if($response->getState() === GameSnapshot_State::PLAYING) {
                // 
            }
            
            /**
             * O jogo interrompe a partida para mudar a posse de bola. 
             * Isso acontece somente quando o tempo do chute acaba (veja a propriedade shot_clock). A bola será dada ao goleiro do time de defesa, 
             * e o próximo estado será "ouvindo", então os bots não terão tempo de se reorganizar antes do próximo turno.
             */
            if($response->getState() === GameSnapshot_State::SHIFTING) {
                // 
            }

            /**
             * O jogo pode terminar após qualquer fase.
             */
            if($response->getState() === GameSnapshot_State::OVER) {
                // 
            }
        }
    }

    private function onListening(GameInspector $inspector, IBot $bot) : void {
        $playerState = $this->definePlayerState($inspector);
        $isGoalkeeper = $bot->number === SPECS::GOALKEEPER_NUMBER;

        $orders = [];

        if($isGoalkeeper) {
            $orders = $bot->asGoalkeeper($inspector, $playerState);
        }

        if(!$isGoalkeeper) {
            $orders = match ($playerState) {
                 PlayerState::DEFENDING => $bot->onDefending($inspector),
                 PlayerState::HOLDING => $bot->onHolding($inspector),
                 PlayerState::DISPUTING => $bot->onDisputing($inspector),
                 PlayerState::SUPPORTING => $bot->onSupporting($inspector),
            };
        }

        $orderSet = new OrderSet();
        $orderSet->setOrders($orders);
        $this->client->SendOrders($orderSet);
    }

    private function definePlayerState(GameInspector $inspector): PlayerState
    {
        if (!$inspector->getBall()) {
            throw new \RuntimeException('Estado de snapshot inválido - não é possível definir o estado do jogador.');
        }

        if (!$inspector->getPlayer($this->side, $this->number)) {
            throw new \RuntimeException('Não foi possível encontrar o bot no snapshot - não é possível definir o estado do jogador.');
        }

        $ballHolder = $inspector->getBall()->getHolder();
        if (!$ballHolder) {
            return PlayerState::DISPUTING;
        } 
        
        if($ballHolder->getSide() === $this->side) {
            if ($ballHolder->getNumber() === $this->number) {
                return PlayerState::HOLDING;
            }
            return PlayerState::SUPPORTING;
        }

        return PlayerState::DEFENDING;
    }
}
