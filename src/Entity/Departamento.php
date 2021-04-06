<?php
namespace App\Entity;
use App\Repository\DepartamentoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass=DepartamentoRepository::class)
 * @UniqueEntity(
 *     fields={"nombre"},
 *     message="Ya existe un departamento con este nombre"
 * )
 */
class Departamento
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="nombre", type="string", length=100, unique=true, nullable=false)
     * @Assert\NotBlank(message="Asegurese de ingresar el nombre del departamento.")
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity=Pais::class)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Asegurese de seleccionar el país del departamento.")
     */
    private $pais;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = strtoupper($nombre);

        return $this;
    }

    public function getPais(): ?Pais
    {
        return $this->pais;
    }

    public function setPais(?Pais $pais): self
    {
        $this->pais = $pais;

        return $this;
    }
}
