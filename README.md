# openshift_security


## Declare namespace security

under terraform/rules/{NAMESPACE}/{NAMESPACE}.yaml, adde the ingress and egress rules


## Plan and apply rules

```
ansible-playbook -e namespace=backend transform.yaml

```