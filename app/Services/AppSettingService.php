<?php

namespace App\Services;

use App\DataTransferObjects\Configuracion\ConfiguracionAgentesData;
use App\DataTransferObjects\Configuracion\ConfiguracionUsuariosData;
use App\Models\AppSetting;

class AppSettingService
{
    /**
     * Obtiene la configuración de usuarios y clientes para la empresa especificada.
     */
    public function obtenerConfiguracionUsuarios(int $empresaId): ConfiguracionUsuariosData
    {
        $valores = AppSetting::obtenerGrupo('users.', $empresaId);

        return ConfiguracionUsuariosData::desdeValores($valores);
    }

    /**
     * Guarda la configuración de usuarios y clientes de forma atómica.
     */
    public function guardarConfiguracionUsuarios(ConfiguracionUsuariosData $datos, int $empresaId): void
    {
        AppSetting::establecerMuchos($datos->aParesClaveValor(), $empresaId);
    }

    /**
     * Obtiene la configuración de agentes y personal para la empresa especificada.
     */
    public function obtenerConfiguracionAgentes(int $empresaId): ConfiguracionAgentesData
    {
        $valores = AppSetting::obtenerGrupo('agents.', $empresaId);

        return ConfiguracionAgentesData::desdeValores($valores);
    }

    /**
     * Guarda la configuración de agentes y personal de forma atómica.
     */
    public function guardarConfiguracionAgentes(ConfiguracionAgentesData $datos, int $empresaId): void
    {
        AppSetting::establecerMuchos($datos->aParesClaveValor(), $empresaId);
    }
}
